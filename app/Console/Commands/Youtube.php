<?php

namespace App\Console\Commands;

use App\Models\XApiCall;
use App\Models\YoutubeApiCall;
use App\Models\YoutubeApiCallRun;
use App\Models\YoutubePlaylist;
use App\Models\YoutubePlaylistItem;
use App\Models\YoutubeVideo;
use App\Models\YoutubeVideoComment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Youtube extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:youtube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected YoutubeApiCallRun $run;

    protected int $totalVideosCount = 0;
    protected array $insertVideos = [];

    protected int $totalCommentsCount = 0;
    protected array $insertComments = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastRun = YoutubeApiCallRun::where('date', '>=', now()->format('Y-m-d 00:00:00'))
            ->orderByDesc('date')
            ->first();

        if ($lastRun) {

            $runsPerDayArray = [24,12,8,6,4,3,2,1];

            foreach ($runsPerDayArray as $runsPerDay) {

                if ($lastRun->total_videos_count > (env('YOUTUBE_MAX_VIDEOS')??2400)/$runsPerDay &&
                    $lastRun->date >= now()->subHours(24/$runsPerDay)->subMinutes(30)->format('Y-m-d H:i:s')) {

                    return;
                }
            }
        }

        $this->run = new YoutubeApiCallRun();
        $this->run->date = now();

        $this->handleProfile();

        $this->handleVideos();

        YoutubeVideo::truncate();
        foreach (array_chunk($this->insertVideos, 500) as $chunkedVideos) {
            try {
                YoutubeVideo::insert($chunkedVideos);
            } catch (\Exception $e) {}
        }

        foreach (YoutubeVideo::pluck('youtube_id')->toArray() as $id) {
            $this->totalCommentsCount = 0;
            $this->handleComments($id);
        }

        YoutubeVideoComment::truncate();
        foreach (array_chunk($this->insertComments, 500) as $chunkedComments) {
            try {
                YoutubeVideoComment::insert($chunkedComments);
            } catch (\Exception $e) {}
        }

        YoutubePlaylist::truncate();
        $this->handlePlaylists();

        foreach (YoutubePlaylist::pluck('youtube_id')->toArray() as $id) {
            YoutubePlaylistItem::where('youtube_playlist_id', $id)->delete();
            $this->handlePlaylistItems($id);
        }

        $this->run->save();
    }

    /**
     * @param $url
     * @param int $quota
     * @param array $params
     * @return YoutubeApiCall
     * @throws \Exception
     */
    private function createApiCall($url, $quota = 1, $params = []): YoutubeApiCall
    {
        $apiCall = new YoutubeApiCall();
        $apiCall->url = $url;
        $apiCall->quota = $quota;
        $apiCall->params = json_encode($params);
        $apiCall->result = $apiCall->makeRequest();
        $apiCall->save();
        return $apiCall;
    }

    private function handleProfile()
    {
        $url = 'https://www.googleapis.com/youtube/v3/channels';
        $quota = 1;
        $params = [
            'part' => 'snippet,contentDetails,statistics,brandingSettings',
            'id' => env('YOUTUBE_ID')
        ];

        $apiCall = $this->createApiCall($url, $quota, $params);

        if (isset($apiCall->result['items'][0]['id'])) {
            $apiCall->updateProfile($apiCall->result['items'][0]);
        }
    }

    private function handleVideos($url = null, $params = [])
    {
        if ($this->totalVideosCount >= (env('YOUTUBE_MAX_VIDEOS') ?? 2400)) {
            return;
        }

        $url = $url ?: 'https://www.googleapis.com/youtube/v3/search';
        $quota = 100;
        $params = count($params) ? $params : [
            'part' => 'snippet',
            'type' => 'video',
            'order' => 'date',
            'maxResults' => 50,
            'channelId' => env('YOUTUBE_ID')
        ];

        $apiCall = $this->createApiCall($url, $quota, $params);

        if (isset($apiCall->result['items'][0]['id'])) {
            $this->totalVideosCount = $this->totalVideosCount + count($apiCall->result['items']);

            $this->run->total_videos_count = $apiCall->result['pageInfo']['totalResults'];

            $videosByUser = $apiCall->result;

            $ids = '';
            foreach ($apiCall->result['items'] as $key => $item) {
                if (!$key == array_key_first($apiCall->result['items'])) {
                    $ids .= ',';
                }
                $ids .= $item['id']['videoId'];
            }

            $quota = 1;
            $url = 'https://www.googleapis.com/youtube/v3/videos';
            $params = [
                'part' => 'snippet,contentDetails,id,liveStreamingDetails,localizations,player,recordingDetails,statistics,status,topicDetails',
                'id' => $ids
            ];

            $apiCall = $this->createApiCall($url, $quota, $params);

            if (isset($apiCall->result['items'][0]['id'])) {

                $this->insertVideos = $this->insertVideos + $apiCall->prepareVideos($apiCall->result['items']);
            }

            if (isset($videosByUser['nextPageToken'])) {
                $params = [
                    'part' => 'snippet',
                    'type' => 'video',
                    'order' => 'date',
                    'maxResults' => 50,
                    'channelId' => env('YOUTUBE_ID'),
                    'pageToken' => $videosByUser['nextPageToken']
                ];
                $this->handleVideos(null, $params);
            }
        }
    }

    private function handleComments($id, $url = null, $params = [])
    {
        if ($this->totalCommentsCount >= (env('YOUTUBE_MAX_COMMENTS') ?? 100 )) {
            return;
        }

        $url = $url ?: 'https://www.googleapis.com/youtube/v3/commentThreads';
        $quota = 1;
        $params = count($params) ? $params : [
            'part' => 'snippet,replies',
            'videoId' => $id,
            'maxResults' => 100
        ];

        $apiCall = $this->createApiCall($url, $quota, $params);

        if (isset($apiCall->result['items'][0]['id'])) {
            $this->totalCommentsCount = $this->totalCommentsCount + count($apiCall->result['items']);

            $this->insertComments = $this->insertComments + $apiCall->prepareComments($apiCall->result['items']);

            if (isset($apiCall->result['nextPageToken'])) {
                $params = [
                    'part' => 'snippet,replies',
                    'videoId' => $id,
                    'maxResults' => 100,
                    'pageToken' => $apiCall->result['nextPageToken']
                ];
                $this->handleComments($id, null, $params);
            }
        }
    }

    private function handlePlaylists($url = null, $params = [])
    {
        $url = $url ?: 'https://www.googleapis.com/youtube/v3/playlists';
        $quota = 1;
        $params = count($params) ? $params : [
            'part' => 'snippet,id,contentDetails,localizations,player,status',
            'channelId' => env('YOUTUBE_ID'),
            'maxResults' => 50
        ];

        $apiCall = $this->createApiCall($url, $quota, $params);

        if (isset($apiCall->result['items'][0]['id'])) {

            $apiCall->updatePlaylists($apiCall->result['items']);

            if (isset($apiCall->result['nextPageToken'])) {
                $params = [
                    'part' => 'snippet,id,contentDetails,localizations,player,status',
                    'channelId' => env('YOUTUBE_ID'),
                    'maxResults' => 50,
                    'pageToken' => $apiCall->result['nextPageToken']
                ];
                $this->handlePlaylists(null, $params);
            }
        }
    }

    private function handlePlaylistItems($id, $url = null, $params = [])
    {
        $url = $url ?: 'https://www.googleapis.com/youtube/v3/playlistItems';
        $quota = 1;
        $params = count($params) ? $params : [
            'part' => 'snippet,id,contentDetails,status',
            'playlistId' => $id,
            'maxResults' => 50
        ];

        $apiCall = $this->createApiCall($url, $quota, $params);

        if (isset($apiCall->result['items'][0]['id'])) {
            $apiCall->updatePlaylistItems($apiCall->result['items']);

            if (isset($apiCall->result['nextPageToken'])) {
                $params = [
                    'part' => 'snippet,id,contentDetails,status',
                    'playlistId' => $id,
                    'maxResults' => 50,
                    'pageToken' => $apiCall->result['nextPageToken']
                ];
                $this->handlePlaylistItems($id, null, $params);
            }
        }
    }
}
