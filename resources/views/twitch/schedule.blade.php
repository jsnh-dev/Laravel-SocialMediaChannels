<div class="videos-profile-wrapper w-100">
    @include('twitch.profile', ['tab' => 'schedule'])
</div>

@if(isset($schedule) && $schedule)
    @if($schedule->first())
        <div class="d-flex w-100 mt-3">
            <h2 class="flex-1">
                {{ __('The next stream is on ' . (
                    now()->diffInDays(\Carbon\Carbon::parse($schedule->first()->start_time)) > 7
                        ? \Carbon\Carbon::parse($schedule->first()->start_time)->addHours(2)->format('F d, Y')
                        : \Carbon\Carbon::parse($schedule->first()->start_time)->addHours(2)->dayName
                ) . ' at ' . \Carbon\Carbon::parse($schedule->first()->start_time)->addHours(2)->format('g:i A') . ' GMT+2.') }}
            </h2>
        </div>
    @endif
    <div class="d-flex w-100 mt-3">
        <div class="calendar flex-1">
            <div class="calendar-nav">
                <button class="btn calendar-nav-today text-white me-2">{{ __('Today') }}</button>
                <button class="btn calendar-nav-prev border border-secondary-translucent text-hover me-2"><i class="fas fa-chevron-left"></i></button>
                <button class="btn calendar-nav-next border border-secondary-translucent text-hover me-2"><i class="fas fa-chevron-right"></i></button>
                <h2 class="calendar-nav-week-info"></h2>
            </div>
            <div id="calendarContent">
                <table class="table">

                </table>
            </div>
        </div>
    </div>

    <div id="eventModalTrigger" data-bs-toggle="modal" data-bs-target="#eventModal"></div>

    @include('elements.modal', [
        'id' => 'eventModal',
        'attribute' => 'data-close-route=' . route('twitch', ['tab' => 'schedule']),
        'titleElement' => '<div class="event-details-title d-flex flex-1 align-items-center"></div>',
        'class' => 'centered'
    ])
@endif