@if(isset($playlists) && $playlists)
    @foreach($playlists as $playlist)
        <a href="{{ route('youtube.playlist', ['id' => $playlist->youtube_id]) }}"
           class="post-wrapper youtube border border-2 mb-3 text-dark text-decoration-none text-hover"
           data-target="#postModal"
           data-route="{{ route('youtube.playlist', ['id' => $playlist->youtube_id]) }}"
           data-route-comments="{{ route('youtube.video.comments', ['id' => ( request()->v ?? $playlist->videos->first()->youtube_id ?? $playlist->items->first()->youtube_video_id )]) }}"
           data-close-route="{{ route('youtube', ['tab' => 'playlists']) }}"
           data-id="{{ $playlist->youtube_id }}">
            <div class="post-image format16x9 position-relative mb-2 cursor-pointer">
                <img class="w-100 h-100 object-fit-cover" src="{{ str_replace('%{width}', '480', str_replace('%{height}', '272', $playlist->thumbnail_url ?? '')) }}">
                <div class="post-overlay-metrics post-duration p-2">
                    <i class="fa fa-bars-staggered me-2"></i>{{ __($playlist->item_count . ' videos') }}
                </div>
            </div>
            <div class="post-info d-flex">
                <div class="post-description d-flex flex-column align-items-start overflow-hidden">
                    <div class="post-title w-100 text-nowrap overflow-hidden text-overflow-ellipsis text-hover-youtube cursor-pointer font-weight-900">
                        {{ $playlist->title }}
                    </div>
                </div>
            </div>
        </a>
    @endforeach
@endif