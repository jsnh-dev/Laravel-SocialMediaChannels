@if(isset($videos) && $videos)

    @if($videos->count())
        @if($videos->currentPage() == 1)
            <div class="w-100 mb-3 pb-3 mx-2">
                @if($videos->count() > 1)
                    {{ __(number_format($videos->total()) . ' videos were found.') }}
                @else
                    {{ __(number_format($videos->total()) . ' video were found.') }}
                @endif
            </div>
        @endif
    @else
        <div class="m-auto mt-5">
            {{ __('No videos found') }}
        </div>
    @endif

    @foreach($videos as $video)
        <a href="{{ route('twitch.video', ['id' => $video->twitch_id]) }}"
           class="post-wrapper border border-2 mb-3 text-dark text-decoration-none"
           data-target="#postModal"
           data-route="{{ route('twitch.video', ['id' => $video->twitch_id]) }}"
           data-close-route="{{ route('twitch', ['tab' => 'videos']) }}"
           data-id="{{ $video->twitch_id }}">
            <div class="post-image position-relative mb-2 cursor-pointer">
                <img class="w-100" src="{{ str_replace('%{width}', '480', str_replace('%{height}', '272', $video->thumbnail_url ?? '')) }}">
                <div class="post-overlay-metrics post-duration">
                    @if(session('twitch.videos.filter.type') == 'clip')
                        {{ \Carbon\Carbon::parse('00:00:00')->addSeconds((int) $video->duration)->format('i:s') }}
                    @else
                        {{ \Carbon\Carbon::parse('00:00:00')->add(str_replace('h', ' hours ', str_replace('m', ' minutes ', str_replace('s', ' seconds ', $video->duration))))->format('H:i:s') }}
                    @endif
                </div>
                <div class="post-overlay-metrics post-view-count">
                    {{ __(countString($video->view_count) . ' views') }}
                </div>
                <div class="post-overlay-metrics post-time-ago">
                    {{ timeAgo($video->created_at) }}
                </div>
            </div>
            <div class="post-info d-flex">
                @if($video->game)
                    <div class="post-game me-2">
                        <div>
                            <img src="{{ str_replace('{width}', '41', str_replace('{height}', '57', $video->game->box_art_url ?? '')) }}"
                                 alt="{{ $video->game->name }}"
                                 title="{{ $video->game->name }}">
                        </div>
                    </div>
                @endif
                <div class="post-description d-flex flex-column align-items-start overflow-hidden">
                    <div class="post-title w-100 text-nowrap overflow-hidden text-overflow-ellipsis text-hover-twitch cursor-pointer font-weight-900" title="{{ $video->title }}">
                        {{ $video->title }}
                    </div>
                    @if($video->creator)
                        <div class="post-creator-name">
                            <div class="text-secondary text-hover text-decoration-none font-size-08rem">
                                {{ __('Clipped by ' . $video->creator->display_name) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </a>
    @endforeach
@endif