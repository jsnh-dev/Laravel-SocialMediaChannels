<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwitchApiCall extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $clientId;
    protected $bearerToken;

    public $result;

    /**
     * XApiCall constructor.
     */
    public function __construct()
    {
        $this->clientId = env('TWITCH_CLIENT_ID');
        $this->bearerToken = env('TWITCH_BEARER_TOKEN');
    }

    public function makeRequest()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->bearerToken,
            'Client-Id: ' . $this->clientId
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $response = curl_exec($ch);

        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * @param $data
     */
    public function updateProfile($data)
    {
        $profile = TwitchProfile::firstOrCreate();

        $profile->update([
            'twitch_id' => $data['id'],
            'login' => $data['login'] ?? null,
            'display_name' => $data['display_name'] ?? null,
            'type' => $data['type'] ?? null,
            'broadcaster_type' => $data['broadcaster_type'] ?? null,
            'description' => $data['description'] ?? null,
            'profile_image_url' => $data['profile_image_url'] ?? null,
            'offline_image_url' => $data['offline_image_url'] ?? null,
            'view_count' => $data['view_count'] ?? 0,
            'followers_count' => $data['followers_count'] ?? 0,
            'created_at' => $data['created_at'] ?? null,
        ]);
    }

    /**
     * @param $data
     */
    public function createStream($data)
    {
        TwitchStream::truncate();

        TwitchStream::create([
            'twitch_id' => $data['id'] ?? null,
            'game_id' => $data['game_id'] ?? null,
            'game_name' => $data['game_name'] ?? null,
            'title' => $data['title'] ?? null,
            'tags' => isset($data['tags']) && is_array($data['tags'])
                ? json_encode($data['tags'], true)
                : null,
            'thumbnail_url' => $data['thumbnail_url'] ?? null,
            'viewer_count' => $data['viewer_count'] ?? 0,
            'started_at' => isset($data['started_at']) && $data['started_at']
                ? Carbon::parse($data['started_at'])
                : null
        ]);
    }

    /**
     * @param $data
     */
    public function createSchedules($data)
    {
        foreach ($data as $d) {
            TwitchSchedule::create([
                'twitch_id' => $d["id"] ?? null,
                'title' => $d["title"] ?? null,
                'category_id' => $d["category"]["id"] ?? null,
                'category_name' => $d["category"]["name"] ?? null,
                'start_time' => isset($d['start_time']) && $d['start_time']
                    ? Carbon::parse($d['start_time'])
                    : null,
                'end_time' => isset($d['end_time']) && $d['end_time']
                    ? Carbon::parse($d['end_time'])
                    : null
            ]);
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function prepareVideos($data): array
    {
        $videos = [];

        foreach ($data as $key => $d) {
            $videos[$key]['twitch_id'] = $d['id'];
            $videos[$key]['type'] = $d['type'] ?? null;
            $videos[$key]['title'] = $d['title'] ?? null;
            $videos[$key]['description'] = $d['description'] ?? null;
            $videos[$key]['url'] = $d['url'] ?? null;
            $videos[$key]['thumbnail_url'] = $d['thumbnail_url'] ?? null;
            $videos[$key]['view_count'] = $d['view_count'] ?? 0;
            $videos[$key]['duration'] = $d['duration'] ?? null;
            $videos[$key]['created_at'] = isset($d['created_at']) && $d['created_at']
                ? Carbon::parse($d['created_at'])
                : null;
            $videos[$key]['published_at'] = isset($d['published_at']) && $d['published_at']
                ? Carbon::parse($d['published_at'])
                : null;
            $videos[$key]['updated_at'] = now();
        }

        return $videos;
    }

    /**
     * @param $data
     * @return array
     */
    public function prepareClips($data): array
    {
        $creators = [];

        $clips = [];

        foreach ($data as $key => $d) {
            $creators[$d['creator_id']] = $d['creator_name'] ?? null;

            $clips[$d['id']]['twitch_id'] = $d['id'] ?? null;
            $clips[$d['id']]['title'] = $d['title'] ?? null;
            $clips[$d['id']]['game_id'] = $d['game_id'] ?? null;
            $clips[$d['id']]['url'] = $d['url'] ?? null;
            $clips[$d['id']]['embed_url'] = $d['embed_url'] ?? null;
            $clips[$d['id']]['thumbnail_url'] = $d['thumbnail_url'] ?? null;
            $clips[$d['id']]['view_count'] = $d['view_count'] ?? 0;
            $clips[$d['id']]['duration'] = $d['duration'] ?? null;
            $clips[$d['id']]['twitch_creator_id'] = $d['creator_id'] ?? null;
            $clips[$d['id']]['created_at'] = isset($d['created_at']) && $d['created_at']
                ? Carbon::parse($d['created_at'])
                : null;
            $clips[$d['id']]['updated_at'] = now();
        }

        $existingUserIds = TwitchUser::whereIn('twitch_id', array_keys($creators))->pluck('twitch_id')->toArray();
        foreach ($existingUserIds as $existingUserId) {
            if (isset($creators[$existingUserId])) {
                TwitchUser::where('id', $existingUserId)->update(['display_name' => $creators[$existingUserId]]);
                unset($creators[$existingUserId]);
            }
        }

        $createUsers = [];
        foreach ($creators as $id => $name) {
            $createUsers[$id]['twitch_id'] = $id;
            $createUsers[$id]['display_name'] = $name;
        }

        TwitchUser::insert($createUsers);

        return $clips;
    }

    /**
     * @param $data
     */
    public function createGames($data)
    {
        $games = [];

        foreach ($data as $key => $d) {
            $games[$key]['twitch_id'] = $d['id'];
            $games[$key]['name'] = $d['name'] ?? null;
            $games[$key]['box_art_url'] = $d['box_art_url'] ?? null;
            $games[$key]['created_at'] = now();
            $games[$key]['updated_at'] = now();
        }

        TwitchGame::insert($games);
    }

    /**
     * @param $data
     */
    public function updateUser($data)
    {
        $twitchUser = TwitchUser::where('twitch_id', $data['id'])->first() ?? new TwitchUser();

        $user = auth()->check()
            ? auth()->user()
            : $twitchUser->user ?? new User();

        $user->updated_at = Carbon::now();
        $user->save();

        $twitchUser->user_id = $user->id;
        $twitchUser->twitch_id = $data['id'];
        $twitchUser->display_name = $data['display_name'];
        $twitchUser->email = $data['email'];
        $twitchUser->profile_image_url = $data['profile_image_url'];
        $twitchUser->logged_in = true;
        $twitchUser->updated_at = Carbon::now();
        $twitchUser->save();

        return $user;
    }
}
