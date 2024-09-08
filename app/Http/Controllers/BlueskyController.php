<?php

namespace App\Http\Controllers;

use App\Models\BlueskyPost;
use App\Models\BlueskyProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlueskyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('bluesky.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $profile = BlueskyProfile::first();

        return response()->json([
            'view' => view('bluesky.profile')->with(['profile' => $profile])->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareProfile(): \Illuminate\Http\JsonResponse
    {
        $url = route('bluesky');

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
        $posts = BlueskyPost::whereNull('parent_cid')
            ->orderByDesc('created_at')
            ->paginate(4);

        return response()->json([
            'data' => $posts,
            'view' => view('bluesky.posts')->with(['posts' => $posts])->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function post($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $post = BlueskyPost::where('cid', $id)->first();

        if (!$post) {
            return redirect()->to(route('bluesky'));
        }

        return request()->ajax()
            ? response()->json(['view' => view('bluesky.post')->with(['post' => $post])->render()])
            : view('bluesky.index')->with(['post' => $post]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments($id): \Illuminate\Http\JsonResponse
    {
        $comments = BlueskyPost::where('parent_cid', $id)
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'data' => $comments,
            'view' => view('bluesky.comments')->with(['comments' => $comments, 'postId' => $id])->render()
        ]);
    }

    /**
     * @param $postId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function replies($postId, $id): \Illuminate\Http\JsonResponse
    {
        $replies = BlueskyPost::where('parent_cid', $id)
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'data' => $replies,
            'view' => view('bluesky.replies')->with(['replies' => $replies])->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sharePost($id): \Illuminate\Http\JsonResponse
    {
        $url = route('bluesky.post', ['id' => $id]);

        $data = [
            'elements' => getShareElements('', $url),
            'url' => $url
        ];

        return response()->json([
            'view' => view('elements.share')->with($data)->render()
        ]);
    }
}
