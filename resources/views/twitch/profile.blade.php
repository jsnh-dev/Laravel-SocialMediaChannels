@if(isset($profile) && $profile)
    <div class="d-flex flex-1 mb-2 align-items-start">
        <div class="profile-picture-wrapper me-3 {{ isset($stream) && $stream ? 'bg-twitch cursor-pointer is-live' : 'bg-secondary' }}">
            <div class="profile-picture-flag twitch-live-indicator border border-2 border-dark {{ isset($stream) && $stream ? '' : 'd-none' }}">LIVE</div>
            <img class="profile-picture" src="{{ $profile->profile_image_url }}" alt="{{ $profile->display_name }}">
        </div>

        <div class="d-flex flex-column flex-1 pe-3">
            <h2 class="profile-name font-weight-900 mb-2 {{ isset($stream) && $stream ? 'hide-for-medium-down' : '' }}">{{ $profile->display_name }}</h2>
            @if(isset($stream) && $stream)
                <h3 class="stream-title font-size-1rem hide-for-large-down">
                    {{ $stream->title }}
                </h3>
                <div class="d-flex flex-wrap hide-for-large-down">
                    <a href="https://www.twitch.tv/directory/category/{{ strtolower(urlencode(str_replace(' ', '-', $stream->game_name))) }}"
                       class="stream-game-name text-twitch text-hover text-decoration-none my-1 me-3"
                       target="_blank">
                        {{ $stream->game_name }}
                    </a>
                    <div class="stream-tags d-flex">
                        @foreach(json_decode($stream->tags, true) as $tag)
                            <a href="https://www.twitch.tv/directory/all/tags/{{ $tag }}"
                               class="stream-tag text-dark text-decoration-none text-hover my-1 me-2"
                               target="_blank">
                                {{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="ms-auto d-flex align-items-center">
            @if(isset($stream) && $stream)
                <span class="me-3 text-danger font-weight-900">
                    <i class="fa fa-users me-2"></i><span class="stream-viewer-count">{{ number_format($stream->viewer_count) }}</span>
                </span>
                <span class="stream-duration me-3"
                      data-seconds="{{ $stream->duration_seconds }}">
                    {{ $stream->duration }}
                </span>
            @endif

            <button type="button"
                    class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover me-4"
                    title="{{ __('Share') }}"
                    data-route="{{ route('twitch.stream.share', ['tab' => $tab ?? request()->tab]) }}">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </button>

            <a class="post-details-external-link text-dark text-hover"
               href="https://www.twitch.tv/{{ env('TWITCH_LOGIN') }}/{{ $tab ?? request()->tab ?? '' }}"
               title="{{ __('Open on Twitch') }}"
               target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </div>

    @if(isset($stream) && $stream)
    <div class="d-flex flex-column flex-1 pe-3 mb-2">
        <h2 class="profile-name font-weight-900 mb-2 hide-for-large-up">{{ $profile->display_name }}</h2>
        <h3 class="font-size-1rem hide-for-xlarge-up">
            {{ $stream->title }}
        </h3>
        <div class="d-flex flex-wrap hide-for-xlarge-up">
            <a href="https://www.twitch.tv/directory/category/{{ strtolower(urlencode(str_replace(' ', '-', $stream->game_name))) }}"
               class="text-twitch text-hover text-decoration-none my-1 me-3"
               target="_blank">
                {{ $stream->game_name }}
            </a>
            @foreach(json_decode($stream->tags, true) as $tag)
                <a href="https://www.twitch.tv/directory/all/tags/{{ $tag }}"
                   class="stream-tag text-dark text-decoration-none text-hover my-1 me-2"
                   target="_blank">
                    {{ $tag }}
                </a>
            @endforeach
        </div>
    </div>
    @endif
@endif