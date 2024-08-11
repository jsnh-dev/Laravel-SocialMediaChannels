<?php

namespace App\Http\Controllers;

use App\Models\InstagramMedia;
use App\Models\InstagramMediaComment;
use App\Models\InstagramProfile;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $data = [
            'description' => InstagramProfile::first()->biography ?? ''
        ];

        return view('instagram.index')->with($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function stories(): \Illuminate\Http\JsonResponse
    {
        $stories = InstagramMedia::where('media_product_type', 'STORY')
            ->get();

        return response()->json([
            'view' => view('instagram.stories')->with(['stories' => $stories])->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $profile = InstagramProfile::first();

        return response()->json([
            'view' => view('instagram.profile')->with(['profile' => $profile])->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareProfile(): \Illuminate\Http\JsonResponse
    {
        $url = route('instagram');

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
        $posts = InstagramMedia::where('media_product_type', 'FEED')
            ->orWhere('is_shared_to_feed', true)
            ->paginate(3);

        return response()->json([
            'data' => $posts,
            'view' => view('instagram.posts')->with(['posts' => $posts])->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function post($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $post = InstagramMedia::where('instagram_id', $id)->first();

        \request()->merge(['tab' => 'posts']);

        return request()->ajax()
            ? response()->json(['view' => view('instagram.post')->with(['post' => $post])->render()])
            : view('instagram.index')->with(['post' => $post]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sharePost($id): \Illuminate\Http\JsonResponse
    {
        $url = route('instagram.post', ['id' => $id]);

        $data = [
            'elements' => getShareElements('', $url),
            'url' => $url
        ];

        return response()->json([
            'view' => view('elements.share')->with($data)->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments($id): \Illuminate\Http\JsonResponse
    {
        $comments = InstagramMediaComment::where('instagram_media_id', $id)
            ->whereNull('instagram_parent_id')
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'data' => $comments,
            'view' => view('instagram.comments')->with(['comments' => $comments])->render()
        ]);
    }

    /**
     * @param $postId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function replies($postId, $id): \Illuminate\Http\JsonResponse
    {
        $replies = InstagramMediaComment::where('instagram_media_id', $postId)
            ->where('instagram_parent_id', $id)
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'data' => $replies,
            'view' => view('instagram.replies')->with(['replies' => $replies])->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function reels(): \Illuminate\Http\JsonResponse
    {
        $reels = InstagramMedia::where('media_product_type', 'REELS')
            ->paginate(3);

        return response()->json([
            'data' => $reels,
            'view' => view('instagram.reels')->with(['reels' => $reels])->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function reel($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $post = InstagramMedia::where('instagram_id', $id)->first();

        \request()->merge(['tab' => 'reels']);

        return request()->ajax()
            ? response()->json(['view' => view('instagram.post')->with(['post' => $post, 'reel' => true])->render()])
            : view('instagram.index')->with(['reel' => $post]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareReel($id): \Illuminate\Http\JsonResponse
    {
        $url = route('instagram.reel', ['id' => $id]);

        $data = [
            'elements' => getShareElements('', $url),
            'url' => $url
        ];

        return response()->json([
            'view' => view('elements.share')->with($data)->render()
        ]);
    }
}
