@if(isset($playlist) && $playlist)
    <div class="post-details-title-hidden d-none">
        <button type="button"
                class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover ms-auto me-3"
                title="{{ __('Share') }}"
                data-route="{{ route('youtube.playlist.share', ['id' => $playlist->youtube_id, 'v' => ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id )]) }}">
            <i class="fa fa-share"></i>
        </button>
        <a class="post-details-external-link text-dark text-hover me-1"
           href="https://www.youtube.com/watch?v={{ ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id ) }}&list={{ $playlist->youtube_id }}"
           title="{{ __('Open playlist on YouTube') }}"
           target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
    </div>

    <div class="post-details-wrapper" data-id="{{ $playlist->youtube_id }}">

        <div class="post-details-image d-flex w-100 h-100-for-medium-up h-50-for-small-down">
            {!!
                str_replace('videoseries?', ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id ) . '?',
                    str_replace('width="640"', 'width="100%"',str_replace('height="360"', 'height="100%"', $playlist->embed_url))
                )
            !!}
        </div>

        <div class="post-details-comments-wrapper border-start border-0-for-small-down flex-1 d-flex flex-column opacity-0">
            <div class="post-details-caption shown flex-0 border-bottom font-size-08rem d-flex flex-column align-items-start">
                <div class="post-caption-title-wrapper d-flex w-100 align-items-start pt-3 px-3">
                    <h2 class="post-caption-title mb-1">{{ $playlist->title }}</h2>
                </div>
                <div class="text-secondary d-flex px-3 pb-1">
                    <div class="text-nowrap">
                        {{ __($playlist->item_count . ' videos') }}
                    </div>
                    <div class="mx-2">&#x2022;</div>
                    <div class="post-time-ago">
                        {{ timeAgo($playlist->published_at) }}
                    </div>
                    <div class="post-published-at">
                        {{ __(\Carbon\Carbon::parse($playlist->published_at)->format('M d, Y')) }}
                    </div>
                </div>
                <div class="post-description font-size-08rem overflow-x-hidden overflow-y-auto px-3">
                    {!! textToHtml($playlist->description) !!}
                </div>
                <div class="post-playlist-items w-100 overflow-x-hidden overflow-y-auto">
                    @foreach($playlist->items as $item)
                        @if($item->video)
                            <a href="{{ route('youtube.playlist', ['id' => $playlist->youtube_id, 'v' => $item->video->youtube_id]) }}"
                               class="post-playlist-item text-dark text-decoration-none text-hover px-3 py-2 d-flex {{ ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id ) == $item->video->youtube_id ? 'bg-secondary-translucent' : '' }}"
                               data-route="{{ route('youtube.playlist', ['id' => $playlist->youtube_id, 'v' => $item->video->youtube_id]) }}"
                               data-route-comments="{{ route('youtube.video.comments', ['id' => $item->video->youtube_id]) }}"
                               data-close-route="{{ route('youtube', ['tab' => 'playlists']) }}"
                               data-id="{{ $playlist->youtube_id }}">
                                <div class="align-self-center me-2">
                                    @if(( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id ) == $item->video->youtube_id)
                                        <i class="fa fa-play"></i>
                                    @else
                                        {{ $item->position + 1 }}
                                    @endif
                                </div>
                                <div class="post-playlist-item-picture format16x9 me-2">
                                    <img class="w-100 h-100 object-fit-cover" src="{{ str_replace('%{width}', '480', str_replace('%{height}', '272', $item->video->thumbnail_url ?? '')) }}">
                                </div>
                                <div class="post-playlist-item-title flex-1 font-size-1rem">
                                    {{ $item->video->title }}
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="show-more px-3 pb-3">
                    {{ __('...more') }}
                </div>
                <button class="btn show-less p-0 pt-3 text-hover font-weight-900 mx-auto-for-small-down px-3 pb-3">
                    {{ __('Show less') }}
                </button>
            </div>
            <div class="post-details-comments overflow-x-hidden overflow-y-auto flex-1">
                <div class="post-details-comments-inner p-3">

                </div>
            </div>
        </div>

    </div>
@endif