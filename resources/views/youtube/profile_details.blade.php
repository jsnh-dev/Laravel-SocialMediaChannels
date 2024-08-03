<div class="d-flex flex-column">
    <div class="px-3">
        {!! textToHtml($profile->description) !!}
    </div>

    <div class="channel-details mt-3 p-3">
        <h5 class="pb-3">
            {{ __('Channel details') }}
        </h5>
        <div class="d-flex align-items-center">
            <i class="fa fa-globe"></i>
            <a href="https://www.youtube.com/{{ $profile->custom_url ?? ( 'channel/' . $profile->youtube_id ) }}" class="text-dark text-decoration-none" target="_blank">
                www.youtube.com/{{ $profile->custom_url ?? ( 'channel/' . $profile->youtube_id ) }}
            </a>
        </div>
        <div class="d-flex align-items-center mt-3">
            <i class="fa fa-user-check"></i>
            {{ __(countString($profile->subscriber_count) . ' subscribers') }}
        </div>
        <div class="d-flex align-items-center mt-3">
            <i class="fa fa-circle-play"></i>
            {{ __($profile->video_count . ' videos') }}
        </div>
        <div class="d-flex align-items-center mt-3">
            <i class="fa fa-chart-line"></i>
            {{ __(number_format($profile->view_count) . ' views') }}
        </div>
        <div class="d-flex align-items-center mt-3">
            <i class="fa fa-circle-info"></i>
            {{ __('Joined ' . \Carbon\Carbon::parse($profile->published_at)->format('M g, Y')) }}
        </div>
    </div>

    <div class="p-3">
        <button type="button"
                class="share-trigger btn btn-youtube"
                data-route="{{ route('youtube.profile.share') }}">
            <i class="fa fa-share"></i> {{ __('Share channel') }}
        </button>
    </div>

</div>