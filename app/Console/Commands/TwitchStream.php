<?php

namespace App\Console\Commands;

use App\Models\TwitchApiCall;
use Illuminate\Console\Command;

class TwitchStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:twitch_stream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $profile;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://api.twitch.tv/helix/users?login=' . env('TWITCH_USERNAME');

        $apiCall = $this->createApiCall($url, 'users');

        if (isset($apiCall->result['data'][0]['id'])) {

            $this->profile = $apiCall->result['data'][0];

            for ($i = 0; $i < 5; $i++) {

                $this->handleStream();

                event(new \App\Events\UpdateTwitchStream());

                if ($i < 4) {
                    sleep(10);
                }
            }
        }
    }

    /**
     * @param $url
     * @param null $entity
     * @return TwitchApiCall
     */
    private function createApiCall($url, $entity = null): TwitchApiCall
    {
        $apiCall = new TwitchApiCall();
        $apiCall->url = $url;
        $apiCall->entity = $entity;
        $apiCall->result = $apiCall->makeRequest();
        $apiCall->save();
        return $apiCall;
    }

    private function handleStream()
    {
        $url = 'https://api.twitch.tv/helix/streams?user_id=' . $this->profile['id'];

        $apiCall = $this->createApiCall($url, 'streams');

        if (isset($apiCall->result['data'][0]['id'])) {
            $apiCall->createStream($apiCall->result['data'][0]);
        } else {
            \App\Models\TwitchStream::truncate();
        }
    }
}
