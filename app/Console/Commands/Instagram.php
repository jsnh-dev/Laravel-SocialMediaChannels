<?php

namespace App\Console\Commands;

use App\Models\InstagramApiCall;
use App\Models\InstagramMedia;
use App\Models\InstagramMediaComment;
use Illuminate\Console\Command;

class Instagram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:instagram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastApiCall = InstagramApiCall::orderByDesc('id')->first();

        if ($lastApiCall && $lastApiCall->status === '429') {
            $this->redoExceededCall($lastApiCall);

            if ($this->lastApiCallRateExceeded()) {
                return;
            }
        }

        $this->handleProfile();

        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        InstagramMedia::where('media_product_type', 'STORY')->delete();

        $this->handleStories();

        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        InstagramMedia::whereNull('media_product_type')->orWhere('media_product_type', '!=', 'STORY')->delete();

        $this->handleMedia();

        $media = InstagramMedia::whereNull('instagram_parent_id')
            ->whereIn('media_product_type', ['FEED', 'REELS'])
            ->get();

        foreach ($media as $m) {
            if ($this->lastApiCallRateExceeded()) {
                return;
            }

            InstagramMediaComment::where('instagram_media_id', $m->instagram_id)->delete();

            $this->handleComments($m->instagram_id);
        }
    }

    private function redoExceededCall($lastApiCall)
    {
        $this->createApiCall(
            $lastApiCall->url,
            $lastApiCall->entity,
            json_decode($lastApiCall->params, true),
            $lastApiCall->object_id
        );

        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        $media = InstagramMedia::whereNull('instagram_parent_id')
            ->whereIn('media_product_type', ['FEED', 'REELS']);

        if ($lastApiCall->entity === 'comments') {
            $lastObject = InstagramMedia::where('instagram_id', $lastApiCall->object_id)->first();

            $media->where('id', '>', $lastObject->id);
        }

        $media->get();

        foreach ($media as $m) {
            InstagramMediaComment::where('instagram_media_id', $m->instagram_id)->delete();

            $this->handleComments($m->instagram_id);
        }
    }

    private function createApiCall(string $url, string $entity, array $params, string $object_id, $commentId = null)
    {
        $apiCall = new InstagramApiCall();
        $apiCall->url = $url;
        $apiCall->entity = $entity;
        $apiCall->params = json_encode($params);
        $apiCall->object_id = $object_id;

        $result = $apiCall->makeRequest($params);

        switch ($apiCall->entity) {
            case 'profile':
                $this->updateProfile($result, $apiCall);
                break;
            case 'stories':
                $this->updateStories($result, $apiCall);
                break;
            case 'media':
                $this->updateMedia($result, $apiCall);
                break;
            case 'comments':
                if (!$this->updateComments($result, $apiCall, $commentId)) {
                    return;
                }
                break;
        }

        if (isset($result['error']['code'])) {
            $apiCall->error_code = $result['error']['code'];
        }

        $apiCall->save();

        if (isset($result['paging']['cursors']['after'])) {
            $params['after'] = $result['paging']['cursors']['after'];
            $this->createApiCall($apiCall->url, $apiCall->entity, $params, $apiCall->object_id);
        }
    }

    private function handleProfile()
    {
        $this->createApiCall(
            InstagramApiCall::getProfileUrl(),
            InstagramApiCall::getProfileEntity(),
            InstagramApiCall::getProfileParams(),
            env('INSTAGRAM_PROFILE_ID')
        );
    }

    private function updateProfile($result, InstagramApiCall $apiCall)
    {
        if (isset($result['id'])) {
            $apiCall->updateProfile($result);
        }
    }

    private function handleStories()
    {
        $this->createApiCall(
            InstagramApiCall::getStoriesUrl(),
            InstagramApiCall::getStoriesEntity(),
            InstagramApiCall::getStoriesParams(),
            env('INSTAGRAM_PROFILE_ID')
        );
    }

    /**
     * @param $result
     * @param InstagramApiCall $apiCall
     */
    private function updateStories($result, InstagramApiCall $apiCall)
    {
        if (isset($result['data'][0]['id'])) {
            foreach ($result['data'] as $data) {
                $apiCall->updateMedia($data);
            }
        }
    }

    private function handleMedia()
    {
        $this->createApiCall(
            InstagramApiCall::getMediaUrl(),
            InstagramApiCall::getMediaEntity(),
            InstagramApiCall::getMediaParams(),
            env('INSTAGRAM_PROFILE_ID')
        );
    }

    /**
     * @param $result
     * @param InstagramApiCall $apiCall
     */
    private function updateMedia($result, InstagramApiCall $apiCall)
    {
        if (isset($result['data'][0]['id'])) {
            foreach ($result['data'] as $data) {
                $apiCall->updateMedia($data);

                if (isset($data['children']['data'][0]['id'])) {
                    foreach ($data['children']['data'] as $d) {
                        $d['parent_id'] = $data['id'];
                        $apiCall->updateMedia($d);
                    }
                }
            }
        }
    }

    private function handleComments($mediaId)
    {
        $this->createApiCall(
            InstagramApiCall::getCommentsUrl($mediaId),
            InstagramApiCall::getCommentsEntity(),
            InstagramApiCall::getCommentsParams(),
            $mediaId
        );
    }

    /**
     * @param $result
     * @param InstagramApiCall $apiCall
     * @param $commentId
     */
    private function updateComments($result, InstagramApiCall $apiCall, $commentId): bool
    {
        if (isset($result['data'][0]['id'])) {
            foreach ($result['data'] as $data) {
                if ($commentId && $commentId != $data['id']) {
                    continue;
                }

                $apiCall->updateComments($data);

                if (isset($data['replies']['data'][0]['id'])) {
                    foreach ($data['replies']['data'] as $d) {
                        $d['parent_id'] = $data['id'];
                        $d['media']['id'] = $data['media']['id'];
                        $apiCall->updateComments($d);
                    }
                }

                if (isset($data['replies']['paging']['cursors']['after'])) {
                    $apiCall->save();
                    $params['fields'] = 'media,id,from,parent_id,timestamp,text,like_count,replies.after('.$data['replies']['paging']['cursors']['after'].'){from,id,parent_id,timestamp,text,like_count}';
                    $this->createApiCall($apiCall->url, $apiCall->entity, $params, $data['media']['id'], $data['id']);
                }
            }
            if ($commentId) {
                $apiCall->save();
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function lastApiCallRateExceeded(): bool
    {
        $lastApiCall = InstagramApiCall::orderByDesc('id')->first();
        if ($lastApiCall && $lastApiCall->status === '429') {
            return true;
        }
        return false;
    }
}
