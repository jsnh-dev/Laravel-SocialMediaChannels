<?php

namespace App\Console\Commands;

use App\Models\BlueskyApiCall;
use App\Models\BlueskyPost;
use Illuminate\Console\Command;

class Bluesky extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bluesky';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $token;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new BlueskyApiCall();

        $this->token = $api->getBearerToken();

        if (!$this->token) {
            return;
        }

        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        $this->handleProfile();

        if ($this->lastApiCallRateExceeded()) {
            return;
        }

        BlueskyPost::truncate();

        $this->handlePosts();

        foreach (BlueskyPost::where('reply_count', '>', 0)->get() as $post) {
            if ($this->lastApiCallRateExceeded()) {
                return;
            }

            $this->handleReplies($post->uri);
        }
    }

    private function handleProfile()
    {
        $api = new BlueskyApiCall();

        $api->url = "https://public.api.bsky.app/xrpc/app.bsky.actor.getProfile?actor=" . ENV('BLUESKY_IDENTIFIER');

        $data = $api->makeRequest($this->token);

        if (isset($data['handle'])) {
            $api->updateProfile($data);
        }

        $api->save();
    }

    private function handlePosts()
    {
        $api = new BlueskyApiCall();

        $api->url = "https://public.api.bsky.app/xrpc/app.bsky.feed.getAuthorFeed?actor=" . ENV('BLUESKY_IDENTIFIER') . "&filter=posts_no_replies";

        $data = $api->makeRequest($this->token);

        if (isset($data['feed']) && count($data['feed'])) {
            $api->updatePosts($data['feed']);
        }

        $api->save();
    }

    private function handleReplies($uri)
    {
        $api = new BlueskyApiCall();

        $api->url = "https://public.api.bsky.app/xrpc/app.bsky.feed.getPostThread?uri=" . $uri;

        $data = $api->makeRequest($this->token);

        if (isset($data['thread']['replies']) && count($data['thread']['replies'])) {
            $api->updatePosts($data['thread']['replies']);
        }

        $api->save();
    }

    /**
     * @return bool
     */
    private function lastApiCallRateExceeded(): bool
    {
        $lastApiCall = BlueskyApiCall::orderByDesc('id')->first();
        if ($lastApiCall && $lastApiCall->status === '429') {
            return true;
        }
        return false;
    }
}
