<nav id="twitchNav" class="navbar border-bottom border-secondary-translucent">
    <div class="w-100 h-100">
        <ul class="nav nav-tabs d-flex flex-row align-items-start border-0">

            <li class="nav-item">
                <button id="tabStreamTrigger"
                        class="btn border-0 text-hover content-navbar-button twitch-link {{ !request()->has('tab') || request()->tab == 'stream' ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tabStreamContent"
                        data-route="{{ route('twitch.stream') }}"
                        data-scroller="#tabStreamContent"
                        data-wrapper=".stream"
                        data-loader="streamLoader"
                        data-name="stream"
                        type="button"
                        role="tab"
                        aria-controls="tabStreamContent"
                        aria-selected="true">
                    {{ __('Stream') }}
                    <div class="nav-item-flag-dot twitch-live-indicator ms-2 {{ \App\Models\TwitchStream::first() ? '' : 'd-none' }}"></div>
                </button>
            </li>

            <li class="nav-item">
                <button id="tabScheduleTrigger"
                        class="btn border-0 text-hover content-navbar-button twitch-link {{ request()->tab == 'schedule' ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tabScheduleContent"
                        data-route="{{ route('twitch.schedule') }}"
                        data-scroller="#tabScheduleContent"
                        data-wrapper=".schedule"
                        data-loader="scheduleLoader"
                        data-name="schedule"
                        type="button"
                        role="tab"
                        aria-controls="tabScheduleContent"
                        aria-selected="true">
                    {{ __('Schedule') }}
                </button>
            </li>

            <li class="nav-item">
                <button id="tabVideosTrigger"
                        class="btn border-0 text-hover content-navbar-button twitch-link {{ request()->tab == 'videos' ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tabVideosContent"
                        data-route="{{ route('twitch.videos') }}"
                        data-scroller="#tabVideosContent"
                        data-wrapper=".videos"
                        data-loader="videosLoader"
                        data-name="videos"
                        type="button"
                        role="tab"
                        aria-controls="tabVideosContent"
                        aria-selected="true">
                    {{ __('Videos') }}
                </button>
            </li>

            <li class="nav-item">
                <button type="button" class="btn border-0 text-hover content-navbar-button twitch-link chat {{ !session()->has('twitch.chat') || session('twitch.chat') ? 'active' : '' }}">
                    {{ __('Chat') }}<i class="fa-solid fa-toggle-{{ !session()->has('twitch.chat') || session('twitch.chat') ? 'on' : 'off' }} ms-2"></i>
                </button>
            </li>
        </ul>
    </div>
</nav>