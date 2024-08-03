<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeApiCall extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $result;

    public function makeRequest()
    {
        $params = json_decode($this->params, true);
        $params['key'] = env('YOUTUBE_API_KEY');

        $ch = curl_init();

        $url = $this->url;

        $url .= '?' . http_build_query($params);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * @param $data
     */
    public function updateProfile($data)
    {
        $profile = YoutubeProfile::firstOrCreate();

        $profile->update([
            'youtube_id' => $data['id'],
            'title' => $data['snippet']['title'] ?? null,
            'custom_url' => $data['snippet']['customUrl'] ?? null,
            'description' => $data['snippet']['description'] ?? null,
            'thumbnail_url' => $data['snippet']['thumbnails']['high']['url'] ?? null,
            'banner_external_url' => isset($data['brandingSettings']['image']['bannerExternalUrl'])
                ? $data['brandingSettings']['image']['bannerExternalUrl']  . '=w2120'
                : null,
            'view_count' => $data['statistics']['viewCount'] ?? 0,
            'subscriber_count' => $data['statistics']['subscriberCount'] ?? 0,
            'video_count' => $data['statistics']['videoCount'] ?? 0,
            'published_at' => isset($data['snippet']['publishedAt']) && $data['snippet']['publishedAt']
                ? Carbon::parse($data['snippet']['publishedAt'])
                : null
        ]);
    }

    /**
     * @param $data
     * @return array
     */
    public function prepareVideos($data): array
    {
        $videos = [];

        foreach ($data as $key => $d) {
            $videos[$d['id']]['youtube_id'] = $d['id'] ?? null;
            $videos[$d['id']]['title'] = $d['snippet']['title'] ?? null;
            $videos[$d['id']]['description'] = $d['snippet']['description'] ?? null;
            $videos[$d['id']]['thumbnail_url'] = $d['snippet']['thumbnails']['maxres']['url']
                ?? $d['snippet']['thumbnails']['standard']['url']
                ?? $d['snippet']['thumbnails']['high']['url']
                ?? $d['snippet']['thumbnails']['medium']['url']
                ?? $d['snippet']['thumbnails']['default']['url']
                ?? null;
            $videos[$d['id']]['embed_url'] = $d['player']['embedHtml'] ?? null;
            $videos[$d['id']]['view_count'] = $d['statistics']['viewCount'] ?? 0;
            $videos[$d['id']]['like_count'] = $d['statistics']['likeCount'] ?? 0;
            $videos[$d['id']]['favorite_count'] = $d['statistics']['favoriteCount'] ?? 0;
            $videos[$d['id']]['comment_count'] = $d['statistics']['commentCount'] ?? 0;

            if (isset($d['contentDetails']['duration'])) {
                $time = \DateTime::createFromFormat("H:i", "00:00");
                $interval = new \DateInterval($d['contentDetails']['duration']);
                $time->add($interval);
                $videos[$d['id']]['duration'] = Carbon::parse($time)->format('H:i:s');
            }

            $videos[$d['id']]['published_at'] = isset($d['snippet']['publishedAt']) && $d['snippet']['publishedAt']
                ? Carbon::parse($d['snippet']['publishedAt'])
                : null;
            $videos[$d['id']]['updated_at'] = now();
        }

        return $videos;
    }

    /**
     * @param $data
     * @return array
     */
    public function prepareComments($data): array
    {
        $comments = [];

        foreach ($data as $d) {
            $comments[$d['id']]['youtube_id'] = $d['id'] ?? null;
            $comments[$d['id']]['youtube_video_id'] = $d['snippet']['videoId'] ?? null;
            $comments[$d['id']]['youtube_parent_id'] = null;
            $comments[$d['id']]['text_display'] = $d['snippet']['topLevelComment']['snippet']['textDisplay'] ?? null;
            $comments[$d['id']]['text_original'] = $d['snippet']['topLevelComment']['snippet']['textOriginal'] ?? null;
            $comments[$d['id']]['author_display_name'] = $d['snippet']['topLevelComment']['snippet']['authorDisplayName'] ?? null;
            $comments[$d['id']]['author_profile_image_url'] = $d['snippet']['topLevelComment']['snippet']['authorProfileImageUrl'] ?? null;
            $comments[$d['id']]['author_channel_url'] = $d['snippet']['topLevelComment']['snippet']['authorChannelUrl'] ?? null;
            $comments[$d['id']]['author_channel_id'] = $d['snippet']['topLevelComment']['snippet']['authorChannelId']['value'] ?? null;
            $comments[$d['id']]['like_count'] = $d['snippet']['topLevelComment']['snippet']['likeCount'] ?? 0;
            $comments[$d['id']]['reply_count'] = $d['snippet']['totalReplyCount'] ?? 0;
            $comments[$d['id']]['published_at'] = isset($d['snippet']['topLevelComment']['snippet']['publishedAt']) && $d['snippet']['topLevelComment']['snippet']['publishedAt']
                ? Carbon::parse($d['snippet']['topLevelComment']['snippet']['publishedAt'])
                : null;

            if (isset($d['replies']['comments'])) {
                foreach ($d['replies']['comments'] as $reply) {
                    $comments[$reply['id']]['youtube_id'] = $reply['id'] ?? null;
                    $comments[$reply['id']]['youtube_video_id'] = $reply['snippet']['videoId'] ?? null;
                    $comments[$reply['id']]['youtube_parent_id'] = $reply['snippet']['parentId'] ?? null;
                    $comments[$reply['id']]['text_display'] = $reply['snippet']['textDisplay'] ?? null;
                    $comments[$reply['id']]['text_original'] = $reply['snippet']['textOriginal'] ?? null;
                    $comments[$reply['id']]['author_display_name'] = $reply['snippet']['authorDisplayName'] ?? null;
                    $comments[$reply['id']]['author_profile_image_url'] = $reply['snippet']['authorProfileImageUrl'] ?? null;
                    $comments[$reply['id']]['author_channel_url'] = $reply['snippet']['authorChannelUrl'] ?? null;
                    $comments[$reply['id']]['author_channel_id'] = $reply['snippet']['authorChannelId']['value'] ?? null;
                    $comments[$reply['id']]['like_count'] = $reply['snippet']['likeCount'] ?? 0;
                    $comments[$reply['id']]['reply_count'] = 0;
                    $comments[$reply['id']]['published_at'] = isset($reply['snippet']['publishedAt']) && $reply['snippet']['publishedAt']
                        ? Carbon::parse($reply['snippet']['publishedAt'])
                        : null;
                }
            }
        }

        return $comments;
    }

    /**
     * @param $data
     */
    public function updatePlaylists($data)
    {
        $playlists = [];

        foreach ($data as $d) {
            $playlists[$d['id']]['youtube_id'] = $d['id'] ?? null;
            $playlists[$d['id']]['title'] = $d['snippet']['title'] ?? null;
            $playlists[$d['id']]['description'] = $d['snippet']['description'] ?? null;
            $playlists[$d['id']]['thumbnail_url'] = $d['snippet']['thumbnails']['maxres']['url']
                ?? $d['snippet']['thumbnails']['standard']['url']
                ?? $d['snippet']['thumbnails']['high']['url']
                ?? $d['snippet']['thumbnails']['medium']['url']
                ?? $d['snippet']['thumbnails']['default']['url']
                ?? null;
            $playlists[$d['id']]['embed_url'] = $d['player']['embedHtml'] ?? null;
            $playlists[$d['id']]['item_count'] = $d['contentDetails']['itemCount'] ?? 0;
            $playlists[$d['id']]['published_at'] = isset($d['snippet']['publishedAt']) && $d['snippet']['publishedAt']
                ? Carbon::parse($d['snippet']['publishedAt'])
                : null;
        }

        YoutubePlaylist::insert($playlists);
    }

    /**
     * @param $data
     */
    public function updatePlaylistItems($data)
    {
        $items = [];

        foreach ($data as $d) {
            $items[$d['id']]['youtube_id'] = $d['id'] ?? null;
            $items[$d['id']]['youtube_playlist_id'] = $d['snippet']['playlistId'] ?? null;
            $items[$d['id']]['youtube_video_id'] = $d['snippet']['resourceId']['videoId'] ?? null;
            $items[$d['id']]['position'] = $d['snippet']['position'] ?? 0;
        }

        YoutubePlaylistItem::insert($items);
    }
}
