@extends('layouts.app')

@section('title')
    - Instagram
@endsection

@section('description'){{ $description ?? '' }}@endsection

@section('script')
    @vite(['resources/js/post.js', 'resources/js/instagram.js'])
@endsection

@section('style')
    @vite(['resources/sass/instagram.scss'])
@endsection

@section('content')
    <div class="wrapper">

        @if(isset($post) && $post)
            <button type="button"
                    class="d-none hidden-post-modal-trigger"
                    data-id="{{ $post->instagram_id }}"
                    data-route="{{ route('instagram.post', ['id' => $post->instagram_id]) }}"
                    data-route-comments="{{ route('instagram.post.comments', ['id' => $post->instagram_id]) }}"
                    data-close-route="{{ route('instagram', ['tab' => 'posts']) }}"
                    data-target="#postModal"></button>
        @endif

        @if(isset($reel) && $reel)
            <button type="button"
                    class="d-none hidden-post-modal-trigger"
                    data-id="{{ $reel->instagram_id }}"
                    data-route="{{ route('instagram.reel', ['id' => $reel->instagram_id]) }}"
                    data-route-comments="{{ route('instagram.post.comments', ['id' => $reel->instagram_id]) }}"
                    data-close-route="{{ route('instagram', ['tab' => 'reels']) }}"
                    data-target="#postModal"></button>
        @endif

        <div class="container instagram">

            <div class="row mt-2">

                <div class="col-12">
                    <div class="profile mb-4">
                        @include('instagram.profile')
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-12">
                    <ul class="nav nav-tabs d-flex justify-content-center border-bottom-0 border-top border-secondary d-none mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button id="tabPostsTrigger"
                                    class="nav-link border-is-top mx-3 {{ !request()->has('tab') || request()->tab == 'posts' ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tabPostsContent"
                                    data-route="{{ route('instagram.posts') }}"
                                    data-wrapper=".posts"
                                    data-loader="postsLoader"
                                    data-name="posts"
                                    type="button"
                                    role="tab"
                                    aria-controls="tabPostsContent"
                                    aria-selected="true">
                                {{ __('Posts') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button id="tabReelsTrigger"
                                    class="nav-link border-is-top mx-3 {{ request()->tab == 'reels' ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tabReelsContent"
                                    data-route="{{ route('instagram.reels') }}"
                                    data-wrapper=".reels"
                                    data-loader="reelsLoader"
                                    data-name="reels"
                                    type="button"
                                    role="tab"
                                    aria-controls="tabReelsContent"
                                    aria-selected="true">
                                {{ __('Reels') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="tabPostsContent"
                             class="tab-pane fade {{ !request()->has('tab') || request()->tab == 'posts' ? 'active show' : '' }}"
                             role="tabpanel"
                             aria-labelledby="tabPostsTrigger"
                             tabindex="0">
                            <div class="posts mb-4 d-flex flex-wrap">
                                @include('instagram.posts')
                            </div>
                        </div>
                        <div id="tabReelsContent"
                             class="tab-pane fade {{ request()->tab == 'reels' ? 'active show' : '' }}"
                             role="tabpanel"
                             aria-labelledby="tabReelsTrigger"
                             tabindex="0">
                            <div class="reels mb-4 d-flex flex-wrap">
                                @include('instagram.reels')
                            </div>
                        </div>
                    </div>

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

        </div>

    </div>
@endsection