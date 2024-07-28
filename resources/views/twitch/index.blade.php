@extends('layouts.app')

@section('title')
    - Twitch
@endsection

@section('script')
    @vite(['resources/js/post.js', 'resources/js/twitch.js'])
    <script src="https://player.twitch.tv/js/embed/v1.js"></script>
@endsection

@section('style')
    @vite(['resources/sass/twitch.scss'])
@endsection

@section('content')
    <div class="wrapper">

        <div class="twitch">

            <input type="hidden" id="username" value="{{ env('TWITCH_LOGIN') }}">

            @if(isset($video) && $video)
                <button type="button"
                        class="d-none hidden-post-modal-trigger"
                        data-id="{{ $video->twitch_id }}"
                        data-route="{{ route('twitch.video', ['id' => $video->twitch_id]) }}"
                        data-close-route="{{ route('twitch', ['tab' => 'videos']) }}"
                        data-target="#postModal"></button>
            @endif

            @if(isset($event) && $event)
                <button type="button"
                        class="d-none hidden-event-modal-trigger"
                        data-id="{{ $event->twitch_id }}"
                        data-date="{{ $event->start_time }}"
                        data-route="{{ route('twitch.event', ['id' => $event->twitch_id]) }}"
                        data-target="#eventModal"></button>
            @endif

            @include('twitch.nav')

            @include('twitch.content')

            <div id="postModalTrigger" data-bs-toggle="modal" data-bs-target="#postModal"></div>

            @include('elements.modal', [
                'id' => 'postModal',
                'class' => 'max-width',
                'titleElement' => '<div class="post-details-title d-flex flex-1 align-items-center"></div>',
                'bodyClass' => 'p-0 overflow-hidden',
                'bodyInnerClass' => 'h-100 position-relative',
                'endContent' =>
                    '<div class="post-nav post-next">
                        <button type="button" class="post-nav-button btn border-0 text-white">
                            <i class="fa fa-chevron-right fa-2x"></i>
                        </button>
                    </div>
                    <div class="post-nav post-previous">
                        <button type="button" class="post-nav-button border-0 btn text-white">
                            <i class="fa fa-chevron-left fa-2x"></i>
                        </button>
                    </div>'
            ])
        </div>
    </div>
@endsection