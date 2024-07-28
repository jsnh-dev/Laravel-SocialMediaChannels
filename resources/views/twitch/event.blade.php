@if(isset($event) && $event && isset($profile) && $profile)
    <div class="d-flex flex-column align-items-start">
        <div class="event-details-title-hidden d-none">
            <div class="profile-picture-wrapper me-3 bg-secondary">
                <img class="profile-picture" src="{{ $profile->profile_image_url }}" alt="{{ $profile->display_name }}">
            </div>
            <div class="d-flex flex-column flex-1 pe-3">
                <h2 class="profile-name font-weight-900 mb-2">{{ $profile->display_name }}</h2>
            </div>
            <button type="button"
                    class="share-trigger btn p-0 text-nowrap text-dark text-decoration-none text-hover ms-auto me-2"
                    title="{{ __('Share') }}"
                    data-route="{{ route('twitch.event.share', ['id' => $event->twitch_id]) }}">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </button>
        </div>

        <h4 class="m-0 w-100">
            {{ $event->title }}
        </h4>

        @if($event->category_name)
            <a href="https://www.twitch.tv/directory/category/{{ strtolower(urlencode(str_replace(' ', '-', $event->category_name))) }}"
               class="stream-game-name text-twitch text-hover text-decoration-none mt-3"
               target="_blank">
                {{ $event->category_name }}
            </a>
        @endif

        <div class="mt-3">
            {{ __(\Carbon\Carbon::parse($event->start_time)->addHours(2)->format('l, M d, Y - g:i A') . ' - ' . \Carbon\Carbon::parse($event->end_time)->addHours(2)->format('g:i A') . ' GMT+2') }}
        </div>
    </div>
@endif