<?php

namespace App\Http\Controllers;

use App\Models\TwitchClip;
use App\Models\TwitchProfile;
use App\Models\TwitchSchedule;
use App\Models\TwitchStream;
use App\Models\TwitchVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TwitchController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('twitch.index')->with(['footer' => false]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(): \Illuminate\Http\RedirectResponse
    {
        auth()->user()->twitch->update([
            'user_id' => null,
            'email' => null,
            'profile_image_url' => null,
            'logged_in' => 0
        ]);

        session()->forget('twitch');

        if (!auth()->user()->hasActiveLogins()) {
            auth()->logout();
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleChat(Request $request): \Illuminate\Http\JsonResponse
    {
        session(['twitch.chat' => filter_var($request->toggle, FILTER_VALIDATE_BOOLEAN)]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function stream(): \Illuminate\Http\JsonResponse
    {
        $data = [
            'stream' => TwitchStream::first() ?? null,
            'profile' => TwitchProfile::first() ?? null
        ];

        return response()->json([
            'view' => view('twitch.stream')->with($data)->render()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareStream(): \Illuminate\Http\JsonResponse
    {
        $url = route('twitch', ['tab' => \request()->tab ?? 'stream']);

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
    public function schedule(): \Illuminate\Http\JsonResponse
    {
        $schedule = TwitchSchedule::with('category')->get();

        foreach ($schedule as $s) {
            $s->duration = Carbon::parse($s->start_time)->diffInHours($s->end_time);
        }

        $data = [
            'stream' => TwitchStream::first() ?? null,
            'profile' => TwitchProfile::first() ?? null,
            'schedule' => $schedule
        ];

        return response()->json([
            'data' => $schedule,
            'view' => view('twitch.schedule')->with($data)->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function event($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $event = TwitchSchedule::where('twitch_id', $id)->first();

        \request()->merge(['tab' => 'schedule']);

        $data = [
            'profile' => TwitchProfile::first() ?? null,
            'event' => $event
        ];

        return request()->ajax()
            ? response()->json(['view' => view('twitch.event')->with($data)->render()])
            : view('twitch.index')->with(['event' => $event, 'footer' => false]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareEvent($id): \Illuminate\Http\JsonResponse
    {
        $url = route('twitch.event', ['id' => $id]);

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
        if (!session('twitch.videos.filter.type')) {
            session(['twitch.videos.filter.type' => 'archive']);
        }
        if (!session('twitch.videos.filter.type_top')) {
            session(['twitch.videos.filter.type_top' => 'top_24h']);
        }

        if (session('twitch.videos.filter.type') == 'clip') {
            $videos = TwitchClip::where(function($q) {
                switch (session('twitch.videos.filter.type_top')) {
                    case 'top_24h':
                        $q->where('created_at', '>=', now()->subHours(24)->format('Y-m-d H:i:s'));
                        break;
                    case 'top_7d':
                        $q->where('created_at', '>=', now()->subDays(7)->format('Y-m-d H:i:s'));
                        break;
                    case 'top_30d':
                        $q->where('created_at', '>=', now()->subDays(30)->format('Y-m-d H:i:s'));
                        break;
                }
            })->orderByDesc('view_count');
        } else {
            $videos = TwitchVideo::where('type', session('twitch.videos.filter.type'))->orderByDesc('published_at');
        }

        if (session('twitch.videos.filter.search')) {
            $videos->where('title', 'like', '%' . session('twitch.videos.filter.search') . '%');
        }

        $videos = $videos->paginate(20);

        $data = [
            'videos' => $videos
        ];

        return response()->json([
            'data' => $videos,
            'view' => view('twitch.videos')->with($data)->render()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterVideos(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->has('type')) {
            if ($request->type) {
                session(['twitch.videos.filter.type' => $request->type]);
            } else {
                session()->forget('twitch.videos.filter.type');
            }
        }

        if (session('twitch.videos.filter.type') == 'clip') {
            if ($request->has('type_top')) {
                if ($request->type_top) {
                    session(['twitch.videos.filter.type_top' => $request->type_top]);
                }
            }
        } else {
            session()->forget('twitch.videos.filter.type_top');
        }

        if ($request->has('search')) {
            if ($request->search) {
                session(['twitch.videos.filter.search' => $request->search]);
            } else {
                session()->forget('twitch.videos.filter.search');
            }
        }

        return response()->json([
            'view' => view('twitch.filter')->render()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function video($id): \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        if (session('twitch.videos.filter.type') == 'clip') {
            $video = TwitchClip::where('twitch_id', $id)->first();
        } else {
            $video = TwitchVideo::where('twitch_id', $id)->first();
        }

        \request()->merge(['tab' => 'videos']);

        return request()->ajax()
            ? response()->json(['view' => view('twitch.video')->with(['video' => $video])->render()])
            : view('twitch.index')->with(['video' => $video, 'footer' => false]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareVideo($id): \Illuminate\Http\JsonResponse
    {
        $url = route('twitch.video', ['id' => $id]);

        $data = [
            'elements' => getShareElements('', $url),
            'url' => $url
        ];

        return response()->json([
            'view' => view('elements.share')->with($data)->render()
        ]);
    }
}
