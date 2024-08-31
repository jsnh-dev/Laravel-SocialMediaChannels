<?php

namespace App\Console\Commands;

use App\Models\TwitchApiCall;
use App\Models\TwitchClip;
use App\Models\TwitchGame;
use App\Models\TwitchSchedule;
use App\Models\TwitchVideo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Twitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:twitch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $profile;

    protected $scheduleRuns = 0;

    protected $insertVideos = [];
    protected $insertClips = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('xdebug.max_nesting_level', -1);

        $lastApiCall = TwitchApiCall::orderByDesc('id')->first();
        $lastApiCallExceeded = false;

        if ($lastApiCall && $lastApiCall->status === '429' && in_array($lastApiCall->entity, ['videos', 'clips'])) {
            $lastApiCallExceeded = true;
        }

        $url = 'https://api.twitch.tv/helix/users?login=' . env('TWITCH_USERNAME');

        $apiCall = $this->createApiCall($url, 'users');

        if (isset($apiCall->result['data'][0]['id'])) {

            $this->profile = $apiCall->result['data'][0];

            if ($lastApiCallExceeded) {
                $this->redoExceededCall($lastApiCall);
            }

            $this->handleProfile();

            TwitchSchedule::truncate();
            $this->handleSchedules();

            $this->insertVideos = [];
            $this->handleVideos();
            TwitchVideo::truncate();
            foreach (array_chunk($this->insertVideos, 500) as $chunkedVideos) {
                TwitchVideo::insert($chunkedVideos);
            }

            $this->insertClips = [];
            $this->handleClips();
            TwitchClip::truncate();
            foreach (array_chunk($this->insertClips, 500) as $chunkedClips) {
                TwitchClip::insert($chunkedClips);
            }

            $this->handleGames();
        }
    }

    private function createApiCall($url, $entity = null)
    {
        $apiCall = new TwitchApiCall();
        $apiCall->url = $url;
        $apiCall->entity = $entity;
        $apiCall->result = $apiCall->makeRequest();
        $apiCall->save();
        return $apiCall;
    }

    private function redoExceededCall($lastApiCall)
    {
        if ($lastApiCall->entity == 'videos') {
            $this->insertVideos = [];
            $this->handleVideos($lastApiCall->url, true);
            foreach (array_chunk($this->insertVideos, 500) as $chunkedVideos) {
                TwitchVideo::insert($chunkedVideos);
            }
        } else if ($lastApiCall->entity == 'clips') {
            $this->insertClips = [];
            $this->handleClips($lastApiCall->url, true);
            foreach (array_chunk($this->insertClips, 500) as $chunkedClips) {
                TwitchClip::insert($chunkedClips);
            }
        }
    }

    private function handleProfile()
    {
        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        $url = 'https://api.twitch.tv/helix/channels/followers?broadcaster_id=' . $this->profile['id'];

        $apiCall = $this->createApiCall($url, 'channels.followers');

        if (isset($apiCall->result['total'])) {
            $this->profile['followers_count'] = $apiCall->result['total'];
        }

        $apiCall->updateProfile($this->profile);
    }

    private function handleSchedules($url = null)
    {
        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        $url = $url ?: 'https://api.twitch.tv/helix/schedule' .
            '?broadcaster_id=' . $this->profile['id'] .
            '&first=25' .
            '&start_time=' . str_replace(' ', 'T', now()->subWeeks(2)->format('Y-m-d 00:00:00') . 'Z');

        $apiCall = $this->createApiCall($url, 'schedule');

        if (isset($apiCall->result['data']['segments'][0]['id'])) {
            $apiCall->createSchedules($apiCall->result['data']['segments']);
        }

        $this->scheduleRuns++;

        if (isset($apiCall->result['pagination']['cursor']) && $this->scheduleRuns < 4) {
            $url = 'https://api.twitch.tv/helix/schedule' .
                '?broadcaster_id=' . $this->profile['id'] .
                '&first=25' .
                '&start_time=' . str_replace(' ', 'T', now()->subWeeks(2)->format('Y-m-d 00:00:00') . 'Z') .
                '&after=' . $apiCall->result['pagination']['cursor'];

            $this->handleSchedules($url);
        }
    }

    private function handleVideos($url = null, $skipCheck = false)
    {
        if (!$skipCheck && $this->lastApiCallRateExceeded()) {
            return;
        }

        $url = $url ?: 'https://api.twitch.tv/helix/videos?user_id=' . $this->profile['id'] . '&first=100';

        $apiCall = $this->createApiCall($url, 'videos');

        if (isset($apiCall->result['data'][0]['id'])) {
            $this->insertVideos = $this->insertVideos + $apiCall->prepareVideos($apiCall->result['data']);
        }

        if (isset($apiCall->result['pagination']['cursor'])) {
            $url = 'https://api.twitch.tv/helix/videos?user_id=' . $this->profile['id'] . '&first=100&after=' . $apiCall->result['pagination']['cursor'];
            $this->handleVideos($url);
        }
    }

    private function handleClips($url = null, $skipCheck = false, $onlyOne = false)
    {
        if (!$skipCheck && $this->lastApiCallRateExceeded()) {
            return;
        }

        $url = $url ?: 'https://api.twitch.tv/helix/clips?broadcaster_id=' . $this->profile['id'] . '&first=' . ($onlyOne ? '1' : '100');

        $apiCall = $this->createApiCall($url, 'clips');

        if ($this->lastApiCallBadRequest() && !$onlyOne) {
            $url = str_replace('&first=100', '&first=1', $url);
            $this->handleClips($url, false,true);
        }

        if (isset($apiCall->result['data'][0]['id'])) {
            $this->insertClips = $this->insertClips + $apiCall->prepareClips($apiCall->result['data']);
        }

        if (isset($apiCall->result['pagination']['cursor'])) {
            $url = 'https://api.twitch.tv/helix/clips?broadcaster_id=' . $this->profile['id'] . '&first=' . ($onlyOne ? '1' : '100') . '&after=' . $apiCall->result['pagination']['cursor'];
            $this->handleClips($url, false, $onlyOne);
        }
    }

    private function handleGames()
    {
        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        $clipGameIds = TwitchClip::whereNotNull('game_id')->where('game_id', '!=', '')->pluck('game_id', 'game_id')->toArray();
        $existingGameIds = TwitchGame::pluck('twitch_id', 'twitch_id')->toArray();
        $gameIds = array_diff($clipGameIds, $existingGameIds);

        foreach (array_chunk($gameIds, 100) as $chunkedGameIds) {
            $params = '';
            foreach ($chunkedGameIds as $key => $gameId) {
                if ($key != array_key_first($chunkedGameIds)) {
                    $params .= '&';
                }
                $params .= 'id=' . $gameId;
            }
            $url = 'https://api.twitch.tv/helix/games?' . $params;
            $apiCall = $this->createApiCall($url, 'games');

            if (isset($apiCall->result['data'][0]['id'])) {
                $apiCall->createGames($apiCall->result['data']);
            }
        }
    }

    /**
     * @return bool
     */
    private function lastApiCallRateExceeded(): bool
    {
        $lastApiCall = TwitchApiCall::orderByDesc('id')->first();
        if ($lastApiCall && $lastApiCall->status === '429') {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function lastApiCallBadRequest(): bool
    {
        $lastApiCall = TwitchApiCall::orderByDesc('id')->first();
        if ($lastApiCall && $lastApiCall->status === '400') {
            return true;
        }
        return false;
    }
}
