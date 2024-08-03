<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use App\Models\YoutubePlaylist;
use App\Models\YoutubeProfile;
use App\Models\YoutubeVideo;
use App\Models\YoutubeVideoComment;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('youtube.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $profile = YoutubeProfile::first();

        return response()->json([
            'view' => view('youtube.profile')->with(['profile' => $profile])->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareProfile(): \Illuminate\Http\JsonResponse
    {
        $url = route('youtube');

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
    public function videos(): \Illuminate\Http\JsonResponse
    {
        $videos = YoutubeVideo::paginate(20);

        $data = [
            'videos' => $videos
        ];

        return response()->json([
            'data' => $videos,
            'view' => view('youtube.videos')->with($data)->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function video($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $video = YoutubeVideo::where('youtube_id', $id)->first();

        \request()->merge(['tab' => 'videos']);

        return request()->ajax()
            ? response()->json(['view' => view('youtube.video')->with(['video' => $video])->render()])
            : view('youtube.index')->with(['video' => $video]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments($id): \Illuminate\Http\JsonResponse
    {
        $comments = YoutubeVideoComment::where('youtube_video_id', $id)
            ->whereNull('youtube_parent_id')
            ->paginate(10);

        return response()->json([
            'data' => $comments,
            'view' => view('youtube.comments')->with(['comments' => $comments])->render()
        ]);
    }

    /**
     * @param $videoId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function replies($videoId, $id): \Illuminate\Http\JsonResponse
    {
        $comments = YoutubeVideoComment::where('youtube_video_id', $videoId)
            ->where('youtube_parent_id', $id)
            ->paginate(10);

        return response()->json([
            'data' => $comments,
            'view' => view('youtube.comments')->with(['comments' => $comments])->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareVideo($id): \Illuminate\Http\JsonResponse
    {
        $url = route('youtube.video', ['id' => $id]);

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
    public function playlists(): \Illuminate\Http\JsonResponse
    {
        $playlists = YoutubePlaylist::paginate(20);

        $data = [
            'playlists' => $playlists
        ];

        return response()->json([
            'data' => $playlists,
            'view' => view('youtube.playlists')->with($data)->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function playlist($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $playlist = YoutubePlaylist::with('items.video')->where('youtube_id', $id)->first();

        \request()->merge(['tab' => 'playlists']);

        $v = \request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id;

        return request()->ajax()
            ? response()->json(['view' => view('youtube.playlist')->with(['playlist' => $playlist, 'v' => $v])->render()])
            : view('youtube.index')->with(['playlist' => $playlist, 'v' => $v]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sharePlaylist($id): \Illuminate\Http\JsonResponse
    {
        $playlist = YoutubePlaylist::where('youtube_id', $id)->first();

        $url = route('youtube.playlist', ['id' => $id, 'v' => ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id )]);

        $data = [
            'elements' => getShareElements('', $url),
            'url' => $url
        ];

        return response()->json([
            'view' => view('elements.share')->with($data)->render()
        ]);
    }
}
