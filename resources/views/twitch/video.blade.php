@if(isset($video) && $video)
    <div class="post-details-wrapper" data-id="{{ $video->twitch_id }}">

        <div class="post-details-video w-100 d-flex">
            <iframe width="100%"
                    height="100%"
                    @if($video instanceof \App\Models\TwitchClip)
                    src="https://clips.twitch.tv/embed?clip={{ $video->twitch_id }}&parent=localhost{{ session('darkmode') ? '&darkpopout' : '' }}&autoplay=true&muted=false"
                    @else
                    src="https://player.twitch.tv/?video={{ $video->twitch_id }}&parent=localhost&autoplay=true&muted=false"
                    @endif
                    allowfullscreen>
            </iframe>
        </div>

        <div class="post-details-title-hidden d-none">
            @if($video->game)
                <div class="me-2">
                    <a href="https://www.twitch.tv/directory/category/{{ strtolower(urlencode(str_replace(' ', '-', $video->game->name))) }}"
                       target="_blank">
                        <img src="{{ str_replace('{width}', '29', str_replace('{height}', '40', $video->game->box_art_url ?? '')) }}"
                             alt="{{ $video->game->name }}"
                             title="{{ $video->game->name }}">
                    </a>
                </div>
            @else
                @php
                    $profile = \App\Models\TwitchProfile::first();
                @endphp

                <img class="modal-profile-picture px-1 me-2" alt=" " src="{{ $profile->profile_image_url }}">
            @endif
            <div class="d-flex flex-column flex-1">
                <h3 class="d-flex font-size-1rem">
                    {{ $video->title }}
                </h3>
                <div class="d-flex font-size-08rem text-secondary">
                    @if($video->creator)
                        <a href="https://www.twitch.tv/{{ strtolower($video->creator->display_name) }}"
                           class="text-secondary text-hover text-decoration-none"
                           target="_blank">
                            {{ __('Clipped by ' . $video->creator->display_name) }}
                        </a>
                        <div class="mx-2">&#x2022;</div>
                    @endif
                    <div>
                        {{ __(countString($video->view_count) . ' views') }}
                    </div>
                        <div class="mx-2">&#x2022;</div>
                    <div>
                        {{ timeAgo($video->created_at) }}
                    </div>
                </div>
            </div>

            <button type="button"
                    class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover mx-2"
                    data-route="{{ route('twitch.video.share', ['id' => $video->twitch_id]) }}"
                    title="{{ __('Share') }}">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </button>

            <a class="post-details-external-link text-dark text-hover mx-2"
               @if($video instanceof \App\Models\TwitchClip)
               href="https://www.twitch.tv/{{ env('TWITCH_LOGIN') }}/clip/{{ $video->twitch_id }}"
               @else
               href="https://www.twitch.tv/videos/{{ $video->twitch_id }}"
               @endif
               title="{{ __('Open video on Twitch') }}"
               target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>

    </div>
@endif