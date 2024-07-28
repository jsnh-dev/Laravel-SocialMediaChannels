<?php

namespace App\Http\Controllers;

use App\Models\TwitchProfile;
use App\Models\XProfile;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('home.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function teaser(): \Illuminate\Http\JsonResponse
    {
        $profile = new \stdClass();

        $profile->x = XProfile::first();
        $profile->twitch = TwitchProfile::first();

        return response()->json([
            'view' => view('home.teaser')->with(['profile' => $profile])->render()
        ]);
    }
}
