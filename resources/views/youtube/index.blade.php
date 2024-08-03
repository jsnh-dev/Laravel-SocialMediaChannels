@extends('layouts.app')

@section('title')
    - Youtube
@endsection

@section('script')
    @vite(['resources/js/post.js', 'resources/js/youtube.js'])
@endsection

@section('style')
    @vite(['resources/sass/youtube.scss'])
@endsection

@section('content')
    <div class="wrapper">

        @if(isset($video) && $video)
            <button type="button"
                    class="d-none hidden-post-modal-trigger youtube"
                    data-id="{{ $video->youtube_id }}"
                    data-route="{{ route('youtube.video', ['id' => $video->youtube_id]) }}"
                    data-route-comments="{{ route('youtube.video.comments', ['id' => $video->youtube_id]) }}"
                    data-close-route="{{ route('youtube', ['tab' => 'videos']) }}"
                    data-target="#postModal"></button>
        @endif

        @if(isset($playlist) && $playlist)
            <button type="button"
                    class="d-none hidden-post-modal-trigger youtube"
                    data-id="{{ $playlist->youtube_id }}"
                    data-route="{{ route('youtube.playlist', ['id' => $playlist->youtube_id, 'v' => ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id )]) }}"
                    data-route-comments="{{ route('youtube.video.comments', ['id' => ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id )]) }}"
                    data-close-route="{{ route('youtube', ['tab' => 'playlists']) }}"
                    data-target="#postModal"></button>
        @endif

        <div class="container youtube">

            <div class="row mt-2">

                <div class="col-12">
                    <div class="profile mb-4">
                        @include('youtube.profile')
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-12">
                    <ul class="nav nav-tabs d-flex border-top-0 border-bottom border-secondary d-none mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button id="tabVideosTrigger"
                                    class="nav-link ms-3 border-is-bottom {{ !request()->has('tab') || request()->tab == 'videos' ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tabVideosContent"
                                    data-route="{{ route('youtube.videos') }}"
                                    data-wrapper=".videos"
                                    data-loader="videosLoader"
                                    data-name="videos"
                                    type="button"
                                    role="tab"
                                    aria-controls="tabVideosContent"
                                    aria-selected="true">
                                {{ __('Videos') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button id="tabPlaylistsTrigger"
                                    class="nav-link border-is-bottom ms-3 {{ request()->tab == 'playlists' ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tabPlaylistsContent"
                                    data-route="{{ route('youtube.playlists') }}"
                                    data-wrapper=".playlists"
                                    data-loader="playlistsLoader"
                                    data-name="playlists"
                                    type="button"
                                    role="tab"
                                    aria-controls="tabPlaylistsContent"
                                    aria-selected="true">
                                {{ __('Playlists') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="tabVideosContent"
                             class="tab-pane fade {{ !request()->has('tab') || request()->tab == 'videos' ? 'active show' : '' }}"
                             role="tabpanel"
                             aria-labelledby="tabVideosTrigger"
                             tabindex="0">
                            <div class="videos mb-4 d-flex flex-wrap">
                                @include('youtube.videos')
                            </div>
                        </div>
                        <div id="tabPlaylistsContent"
                             class="tab-pane fade {{ request()->tab == 'playlists' ? 'active show' : '' }}"
                             role="tabpanel"
                             aria-labelledby="tabPlaylistsTrigger"
                             tabindex="0">
                            <div class="playlists mb-4 d-flex flex-wrap">
                                @include('youtube.playlists')
                            </div>
                        </div>
                    </div>

                    <div id="postModalTrigger" data-bs-toggle="modal" data-bs-target="#postModal"></div>

                    @include('elements.modal', [
                        'id' => 'postModal',
                        'class' => 'max-width max-height',
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

        </div>

    </div>
@endsection