<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramApiCall extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function makeRequest($params)
    {
        $params['access_token'] = env('INSTAGRAM_ACCESS_TOKEN');

        $url = $this->url;

        foreach ($params as $key => $value) {
            if ($key == array_key_first($params)) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= $key . '=' . $value;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * @return string
     */
    public static function getProfileUrl(): string
    {
        return 'https://graph.facebook.com/v3.2/' . env('INSTAGRAM_PROFILE_ID');
    }

    /**
     * @return string
     */
    public static function getProfileEntity(): string
    {
        return 'profile';
    }

    /**
     * @return array
     */
    public static function getProfileParams(): array
    {
        return ['fields' => 'id,name,username,website,profile_picture_url,followers_count,follows_count,media_count,biography'];
    }

    /**
     * @return string
     */
    public static function getStoriesUrl(): string
    {
        return 'https://graph.facebook.com/v3.2/' . env('INSTAGRAM_PROFILE_ID') . '/stories';
    }

    /**
     * @return string
     */
    public static function getStoriesEntity(): string
    {
        return 'stories';
    }

    /**
     * @return array
     */
    public static function getStoriesParams(): array
    {
        return ['fields' => 'id,media_product_type,media_type,media_url,permalink,shortcode,timestamp'];
    }

    /**
     * @return string
     */
    public static function getMediaUrl(): string
    {
        return 'https://graph.facebook.com/v3.2/' . env('INSTAGRAM_PROFILE_ID') . '/media';
    }

    /**
     * @return string
     */
    public static function getMediaEntity(): string
    {
        return 'media';
    }

    /**
     * @return array
     */
    public static function getMediaParams(): array
    {
        return ['fields' => 'id,caption,comments_count,like_count,is_shared_to_feed,media_product_type,media_type,media_url,permalink,shortcode,thumbnail_url,timestamp,children{id,media_type,media_url}'];
    }

    /**
     * @param $mediaId
     * @return string
     */
    public static function getCommentsUrl($mediaId): string
    {
        return 'https://graph.facebook.com/v3.2/' . $mediaId . '/comments';
    }

    /**
     * @return string
     */
    public static function getCommentsEntity(): string
    {
        return 'comments';
    }

    /**
     * @return array
     */
    public static function getCommentsParams(): array
    {
        return ['fields' => 'media,from,id,parent_id,timestamp,text,like_count,replies{from,id,parent_id,timestamp,text,like_count}'];
    }

    public function updateProfile($data)
    {
        $profile = InstagramProfile::firstOrCreate();

        $profile->update([
            'instagram_id' => $data["id"],
            'name' => $data["name"] ?? null,
            'username' => $data["username"] ?? null,
            'website' => $data["website"] ?? null,
            'profile_picture_url' => $data["profile_picture_url"] ?? null,
            'followers_count' => $data["followers_count"] ?? 0,
            'follows_count' => $data["follows_count"] ?? 0,
            'media_count' => $data["media_count"] ?? 0,
            'biography' => $data["biography"] ?? null,
        ]);
    }

    public function updateMedia($data)
    {
        InstagramMedia::create(
            [
                'instagram_id' => $data["id"],
                'instagram_parent_id' => $data["parent_id"] ?? null,
                'media_product_type' => $data["media_product_type"] ?? null,
                'media_type' => $data["media_type"] ?? null,
                'media_url' => $data["media_url"] ?? null,
                'caption' => $data["caption"] ?? null,
                'permalink' => $data["permalink"] ?? null,
                'shortcode' => $data["shortcode"] ?? null,
                'is_shared_to_feed' => $data["is_shared_to_feed"] ?? null,
                'thumbnail_url' => $data["thumbnail_url"] ?? null,
                'comments_count' => $data["comments_count"] ?? 0,
                'like_count' => $data["like_count"] ?? 0,
                'timestamp' => isset($data["timestamp"]) && $data["timestamp"] ? Carbon::parse($data["timestamp"]) : null,
            ]
        );
    }

    public function updateComments($data)
    {
        if (isset($data["from"]["id"]) && isset($data["from"]["username"])) {
            InstagramUser::updateOrCreate(
                [
                    'instagram_id' => $data["from"]["id"],
                ],
                [
                    'username' => $data["from"]["username"],
                ]
            );
        }

        InstagramMediaComment::create(
            [
                'instagram_id' => $data["id"],
                'instagram_parent_id' => $data["parent_id"] ?? null,
                'instagram_media_id' => $data['media']['id'] ?? null,
                'instagram_user_id' => $data["from"]["id"] ?? null,
                'text' => $data["text"] ?? null,
                'like_count' => $data["like_count"] ?? 0,
                'timestamp' => isset($data["timestamp"]) && $data["timestamp"] ? Carbon::parse($data["timestamp"]) : null,
            ]
        );
    }
}
