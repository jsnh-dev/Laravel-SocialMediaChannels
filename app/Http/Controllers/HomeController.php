<?php

namespace App\Http\Controllers;

use App\Models\BlueskyProfile;
use App\Models\InstagramProfile;
use App\Models\TwitchProfile;
use App\Models\XProfile;
use App\Models\YoutubeProfile;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $profile = new \stdClass();

        $profile->x = XProfile::first();
        $profile->twitch = TwitchProfile::first();
        $profile->youtube = YoutubeProfile::first();
        $profile->instagram = InstagramProfile::first();
        $profile->bluesky = BlueskyProfile::first();

        return view('home.index')->with(['profile' => $profile]);
    }
}
