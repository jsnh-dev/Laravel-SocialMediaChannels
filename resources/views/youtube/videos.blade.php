@if(isset($videos) && $videos)
    @foreach($videos as $video)
        <a href="{{ route('youtube.video', ['id' => $video->youtube_id]) }}"
           class="post-wrapper youtube border border-2 mb-3 text-dark text-decoration-none text-hover"
           data-target="#postModal"
           data-route="{{ route('youtube.video', ['id' => $video->youtube_id]) }}"
           data-route-comments="{{ route('youtube.video.comments', ['id' => $video->youtube_id]) }}"
           data-close-route="{{ route('youtube', ['tab' => 'videos']) }}"
           data-id="{{ $video->youtube_id }}">
            <div class="post-image format16x9 position-relative mb-2 cursor-pointer">
                <img class="w-100 h-100 object-fit-cover" src="{{ str_replace('%{width}', '480', str_replace('%{height}', '272', $video->thumbnail_url ?? '')) }}">
                <div class="post-overlay-metrics post-duration">
                    @if(\Carbon\Carbon::parse($video->duration) > \Carbon\Carbon::parse('01:00:00'))
                        {{ \Carbon\Carbon::parse($video->duration)->format('H:i:s') }}
                    @else
                        {{ \Carbon\Carbon::parse($video->duration)->format('i:s') }}
                    @endif
                </div>
            </div>
            <div class="post-info d-flex">
                <div class="post-description d-flex flex-column align-items-start overflow-hidden">
                    <div class="post-title w-100 text-nowrap overflow-hidden text-overflow-ellipsis text-hover-youtube cursor-pointer font-weight-900" title="{{ $video->title }}">
                        {{ $video->title }}
                    </div>
                    <div class="post-metrics d-flex text-secondary">
                        <div class="post-view-count text-nowrap">
                            {{ __(countString($video->view_count) . ' views') }}
                        </div>
                        <div class="mx-2">&#x2022;</div>
                        <div class="post-time-ago">
                            {{ timeAgo($video->published_at) }}
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
@endif