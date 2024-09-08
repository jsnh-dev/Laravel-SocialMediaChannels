@extends('layouts.app')

@section('title')
    - Bluesky
@endsection

@section('script')
    @vite(['resources/js/post.js', 'resources/js/bluesky.js'])
@endsection

@section('style')
    @vite(['resources/sass/bluesky.scss'])
@endsection

@section('content')
    <div class="wrapper">

        @if(isset($post) && $post)
            <button type="button"
                    class="d-none hidden-post-modal-trigger bluesky"
                    data-id="{{ $post->cid }}"
                    data-route="{{ route('bluesky.post', ['id' => $post->cid]) }}"
                    data-route-comments="{{ route('bluesky.post.comments', ['id' => $post->cid]) }}"
                    data-close-route="{{ route('bluesky') }}"
                    data-target="#postModal"></button>
        @endif

        <div class="container bluesky">

            <div class="row">

                <div class="col-12">

                    <div class="profile">
                        @include('bluesky.profile')
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-12">

                    <div class="posts mb-4 d-flex flex-wrap">
                        @include('bluesky.posts')
                    </div>

                </div>

            </div>

            <div id="postModalTrigger" data-bs-toggle="modal" data-bs-target="#postModal"></div>

            @include('elements.modal', [
                'id' => 'postModal',
                'class' => 'overflow-visible max-width',
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