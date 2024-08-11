@if(isset($post) && $post)
    @php
        $profile = \App\Models\InstagramProfile::first();
    @endphp

    <div class="post-details-title-hidden d-none">
        <img class="modal-profile-picture px-1 me-2" src="{{ $profile->profile_picture_url }}">{{ $profile->username }}
        <a class="post-details-external-link text-dark text-hover ms-auto me-2"
           href="{{ $post->permalink }}"
           title="{{ __('Open post on Instagram') }}"
           target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
    </div>

    <div class="post-details-wrapper" data-id="{{ $post->instagram_id }}">
        @switch($post->media_type)

            @case('VIDEO')
            <video class="post-details-image"  controls>
                <source src="{{ $post->media_url }}"/>
            </video>
            @break

            @case('CAROUSEL_ALBUM')
            <div class="post-details-image owl-carousel owl-theme owl">
                @foreach($post->children as $child)
                    <div class="owl-image w-100 h-100">
                        <img src="{{ $child->media_url }}">
                        <div class="background-blur-wrapper">
                            <img src="{{ $child->media_url }}">
                        </div>
                    </div>
                @endforeach
            </div>
            @break

            @case('IMAGE')
            <img class="post-details-image" src="{{ $post->media_url }}">
            <div class="background-blur-wrapper">
                <img src="{{ $post->media_url }}">
            </div>
            @break

        @endswitch

        <div class="post-details-comments-wrapper border-start border-0-for-small-down flex-1 d-flex flex-column opacity-0">
            <div class="post-details-caption flex-0 p-3 border-bottom mh-50 d-flex flex-column">
                <h1 class="font-size-08rem font-weight-900 mb-2">{{ $profile->username }}</h1>
                @if($post->caption)
                    <div class="line-height-1-2 mb-2 overflow-auto">{!! textToHtml($post->caption) !!}</div>
                @endif
                @if($post->timestamp)
                    <div class="post-details-timestamp font-size-08rem text-secondary">
                        {{ __(\Carbon\Carbon::parse($post->timestamp)->format('F d, Y')) }}
                    </div>
                @endif
            </div>
            <div class="post-details-comments overflow-x-hidden overflow-y-auto flex-1 p-3">
                <div class="post-details-comments-inner">

                </div>
            </div>
            <div class="post-details-metrics d-flex flex-wrap p-3 border-top">
                <a href="{{ $post->permalink }}" class="text-nowrap text-dark text-decoration-none text-hover me-3" target="_blank">
                    <i class="fa-regular fa-heart"></i>
                    {{ $post->like_count }}
                </a>
                <a href="{{ $post->permalink }}" class="text-nowrap text-dark text-decoration-none text-hover me-3" target="_blank">
                    <i class="fa-regular fa-comment"></i>
                    {{ $post->comments_count }}
                </a>
                <button type="button"
                        @if(isset($home) && $home)
                            data-route="{{ route('home.post.share', ['type' => 'instagram', 'id' => $post->instagram_id]) }}"
                        @else
                            data-route="{{ route('instagram.' . (isset($reel) && $reel ? 'reel' : 'post' ) . '.share', ['id' => $post->instagram_id]) }}"
                        @endif
                        class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover me-3">
                    <i class="fa-regular fa-paper-plane"></i>
                </button>
                <a href="{{ $post->permalink }}" class="ms-auto text-nowrap text-dark text-decoration-none text-hover me-3" target="_blank">
                    <i class="fa-regular fa-bookmark"></i>
                </a>
            </div>
            <div class="d-flex flex-wrap p-3 post-details-add-comment border-top">
                <a href="{{ $post->permalink }}" class="text-nowrap text-secondary text-decoration-none text-hover flex-1" target="_blank">
                    {{ __('Add a comment...') }}
                </a>
            </div>
        </div>
    </div>
@endif