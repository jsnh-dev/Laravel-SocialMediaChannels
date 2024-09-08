@if(isset($post) && $post)
    @php
        $profile = \App\Models\BlueskyProfile::first();
    @endphp

    <div class="post-details-title-hidden d-none">
        <img class="modal-profile-picture px-1 me-2" src="{{ $profile->avatar }}">
        <div class="me-2">
        {{ $profile->display_name }}
        </div>
        <div class="text-secondary">
        {{ '@' . $profile->handle }}
        </div>
        <a class="post-details-external-link text-dark text-hover ms-auto me-2"
           href="{{ $post->permalink }}"
           title="{{ __('Open post on Bluesky') }}"
           target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
    </div>

    <div class="post-details-wrapper" data-id="{{ $post->cid }}">
        <img class="post-details-image {{ $post->media_url ? '' : 'opacity-0' }}" src="{{ $post->media_url ?? '' }}">
        <div class="background-blur-wrapper {{ $post->media_url ? '' : 'opacity-0' }}">
            <img src="{{ $post->media_url ?? '' }}">
        </div>

        <div class="post-details-comments-wrapper border-start border-0-for-small-down flex-1 d-flex flex-column opacity-0">
            <div class="post-details-caption flex-0 p-3 border-bottom mh-50 d-flex flex-column">
                <div class="line-height-1-2 mb-2 overflow-auto">
                    {!! textToHtml($post->text) !!}
                </div>
                <div class="post-details-timestamp font-size-1rem text-secondary">
                    {{ __(\Carbon\Carbon::parse($post->created_at)->format('F d, Y')) }}
                </div>
            </div>
            <div class="post-details-comments overflow-x-hidden overflow-y-auto flex-1 p-3">
                <div class="post-details-comments-inner">

                </div>
            </div>
            <div class="post-details-metrics d-flex flex-wrap p-3 border-top">
                <a href="{{ $post->permalink }}"
                   class="text-nowrap text-dark text-decoration-none text-hover me-4 d-flex align-items-center"
                   target="_blank">
                    <i class="fa-regular fa-message me-2"></i>
                    {{ $post->reply_count }}
                </a>
                <a href="{{ $post->permalink }}"
                   class="text-nowrap text-dark text-decoration-none text-hover me-4 d-flex align-items-center"
                   target="_blank">
                    <i class="fa fa-retweet me-2"></i>
                    {{ $post->repost_count }}
                </a>
                <a href="{{ $post->permalink }}"
                   class="text-nowrap text-dark text-decoration-none text-hover me-4 d-flex align-items-center"
                   target="_blank">
                    <i class="fa-regular fa-heart me-2"></i>
                    {{ $post->like_count }}
                </a>
                <button type="button"
                        data-route="{{ route('bluesky.post.share', ['id' => $post->cid]) }}"
                        class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover ms-auto">
                    <i class="fa-regular fa-paper-plane"></i>
                </button>
            </div>
            <div class="d-flex flex-wrap p-3 post-details-add-comment border-top">
                <a href="{{ $post->permalink }}" class="text-nowrap text-secondary text-decoration-none text-hover flex-1" target="_blank">
                    {{ __('Add a comment...') }}
                </a>
            </div>
        </div>
    </div>
@endif