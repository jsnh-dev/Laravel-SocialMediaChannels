@if(isset($video) && $video)

    @php
        $profile = \App\Models\YoutubeProfile::first();
    @endphp

    <div class="post-details-title-hidden d-none">
        <img class="modal-profile-picture px-1 me-2" alt=" " src="{{ $profile->thumbnail_url }}">{{ $profile->title }}
        <a href="https://www.youtube.com/{{ ( $profile->custom_url ?? ( 'channel/' . $profile->youtube_id ) ) }}?sub_confirmation=1"
            target="_blank"
            class="btn btn-youtube ms-3 py-1">
            {{ __('Subscribe') }}
        </a>
        <button type="button"
                class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover ms-auto me-3"
                data-route="{{ route('youtube.video.share', ['id' => $video->youtube_id]) }}"
                title="{{ __('Share') }}">
            <i class="fa fa-share"></i>
        </button>
        <a class="post-details-external-link text-dark text-hover me-1"
           href="https://www.youtube.com/watch?v={{ $video->youtube_id }}"
           title="{{ __('Open video on YouTube') }}"
           target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
    </div>

    <div class="post-details-wrapper" data-id="{{ $video->youtube_id }}">

        <div class="post-details-image d-flex w-100 h-100-for-medium-up h-50-for-small-down">
            {!! str_replace('width="480"', 'width="100%"', str_replace('height="270"', 'height="100%"', $video->embed_url)) !!}
        </div>

        <div class="post-details-comments-wrapper border-start border-0-for-small-down flex-1 d-flex flex-column opacity-0">
            <div class="post-details-caption hidden flex-0 p-3 border-bottom font-size-08rem d-flex flex-column align-items-start">
                <div class="post-caption-title-wrapper d-flex w-100 align-items-start">
                    <h2 class="post-caption-title mb-1">{{ $video->title }}</h2>
                    <div class="post-details-comment-metrics d-flex align-items-center ms-auto me-2 pe-1 pt-1">
                        <a class="d-flex align-items-center text-dark text-hover text-decoration-none"
                           href="https://www.youtube.com/watch?v={{ $video->youtube_id }}"
                           title="{{ __('Like') }}"
                           target="_blank">
                            <i class="fa fa-thumbs-up"></i>
                            @if($video->like_count)
                                <div class="post-details-comment-likes text-secondary ms-2">
                                    {{ $video->like_count }}
                                </div>
                            @endif
                        </a>
                        <a class="d-flex align-items-center text-dark text-hover text-decoration-none ms-3"
                           href="https://www.youtube.com/watch?v={{ $video->youtube_id }}"
                           title="{{ __('Dislike') }}"
                           target="_blank">
                            <i class="fa fa-thumbs-down"></i>
                        </a>
                    </div>
                </div>
                <div class="text-secondary d-flex pb-1">
                    <div class="post-view-count-string text-nowrap">
                        {{ __(countString($video->view_count) . ' views') }}
                    </div>
                    <div class="post-view-count text-nowrap">
                        {{ __(number_format($video->view_count) . ' views') }}
                    </div>
                    <div class="mx-2">&#x2022;</div>
                    <div class="post-time-ago">
                        {{ timeAgo($video->published_at) }}
                    </div>
                    <div class="post-published-at">
                        {{ __(\Carbon\Carbon::parse($video->published_at)->format('M d, Y')) }}
                    </div>
                </div>
                <div class="post-description font-size-08rem overflow-x-hidden overflow-y-auto">
                    {!! textToHtml($video->description) !!}
                </div>
                <div class="show-more">
                    {{ __('...more') }}
                </div>
                <button class="btn show-less p-0 pt-3 text-hover font-weight-900 mx-auto-for-small-down">
                    {{ __('Show less') }}
                </button>
            </div>
            <div class="post-details-comments overflow-x-hidden overflow-y-auto flex-1">
                <div class="post-comments-count font-size-125rem font-weight-900 d-flex p-3 border-bottom">
                    {{ __($video->comment_count . ' Comments') }}
                </div>
                <div class="post-details-comments-inner p-3">

                </div>
            </div>
        </div>

    </div>
@endif