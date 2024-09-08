<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlueskyApiCall extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function makeRequest($token)
    {
        $ch = curl_init($this->url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getBearerToken(): mixed
    {
        $url = "https://bsky.social/xrpc/com.atproto.server.createSession";

        $postData = json_encode([
            'identifier' => env('BLUESKY_IDENTIFIER'),
            'password' => env('BLUESKY_PASSWORD')
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['accessJwt'])) {
            return $data['accessJwt'];
        }

        return null;
    }

    /**
     * @param $data
     */
    public function updateProfile($data)
    {
        $profile = BlueskyProfile::firstOrCreate();

        $profile->update([
            'did' => $data['did'] ?? null,
            'handle' => $data['handle'] ?? null,
            'display_name' => $data['displayName'] ?? null,
            'description' => $data['description'] ?? null,
            'avatar' => $data['avatar'] ?? null,
            'banner' => $data['banner'] ?? null,
            'followers_count' => $data['followersCount'],
            'follows_count' => $data['followsCount'],
            'posts_count' => $data['postsCount'],
            'created_at' => $data['createdAt'],
        ]);
    }

    /**
     * @param $data
     * @param bool $is_recommendation
     * @param $given
     */
    public function updatePosts($data)
    {
        foreach ($data as $d) {
            BlueskyPost::create([
                'parent_cid' => $d['post']['record']['reply']['parent']['cid'] ?? null,
                'cid' => $d['post']['cid'] ?? null,
                'uri' => $d['post']['uri'] ?? null,
                'author_handle' => $d['post']['author']['handle'] ?? null,
                'author_display_name' => $d['post']['author']['displayName'] ?? null,
                'author_avatar' => $d['post']['author']['avatar'] ?? null,
                'text' => $d['post']['record']['text'] ?? null,
                'media_url' => $d['post']['embed']['images'][0]['fullsize'] ?? null,
                'reply_count' => $d["post"]['replyCount'] ?? null,
                'repost_count' => $d["post"]['repostCount'] ?? null,
                'like_count' => $d["post"]['likeCount'] ?? null,
                'quote_count' => $d["post"]['quoteCount'] ?? null,
                'created_at' => isset($d['post']['record']["createdAt"])
                    ? Carbon::parse($d['post']['record']["createdAt"])
                    : null
            ]);
        }
    }
}
