<div class="tab-content profile-content d-flex">

    <div id="tabStreamContent"
         class="tab-pane fade flex-1 stream-wrapper {{ !request()->has('tab') || request()->tab == 'stream' ? 'active show' : '' }}"
         role="tabpanel"
         aria-labelledby="tabStreamTrigger"
         tabindex="0">
        <div>
            <div class="player d-flex overflow-hidden border-bottom border-secondary-translucent">
                <div id="liveStreamPlayer"></div>
            </div>

            <div class="d-flex p-3">
                <div class="stream d-flex flex-column flex-1">
                    @include('twitch.stream')
                </div>
            </div>
        </div>
    </div>

    <div id="tabScheduleContent"
         class="tab-pane fade flex-1 schedule-wrapper {{ request()->tab == 'schedule' ? 'active show' : '' }}"
         role="tabpanel"
         aria-labelledby="tabScheduleTrigger"
         tabindex="0">
        <div class="schedule d-flex flex-wrap p-3">
            @include('twitch.schedule')
        </div>
    </div>

    <div id="tabVideosContent"
         class="tab-pane fade flex-1 videos-wrapper {{ request()->tab == 'videos' ? 'active show' : '' }}"
         role="tabpanel"
         aria-labelledby="tabVideosTrigger"
         tabindex="0">
        <div class="videos d-flex flex-wrap p-3 {{ session()->has('twitch.chat') && !session('twitch.chat') ? 'chat-inactive' : '' }}">
            @include('twitch.filter')

            @include('twitch.videos')
        </div>
    </div>

    @include('twitch.chat')

</div>