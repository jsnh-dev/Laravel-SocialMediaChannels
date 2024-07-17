<?php

namespace App\Http\Controllers;

use App\Models\XProfile;

class XController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('x.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $profile = XProfile::first();

        return response()->json([
            'view' => view('x.profile')->with(['profile' => $profile])->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareProfile(): \Illuminate\Http\JsonResponse
    {
        $url = route('x');

        $data = [
            'elements' => getShareElements('', $url),
            'url' => $url
        ];

        return response()->json([
            'view' => view('elements.share')->with($data)->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function posts(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'view' => view('x.posts')->render()
        ]);
    }
}
