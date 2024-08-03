@if(isset($profile) && $profile)
    <div class="flex-1 d-flex flex-column position-relative w-100">
        {{-- Profile banner --}}
        @if($profile->banner_external_url && getimagesize($profile->banner_external_url))
            <div class="profile-banner-wrapper d-flex">
                <img class="profile-banner w-100" src="{{ $profile->banner_external_url ?? '' }}" alt=" ">
            </div>
        @endif
        <div class="position-relative">
            <div class="profile-url col-12 d-flex position-absolute left-0 top-2 hide-for-small-down">
                <button type="button"
                        class="share-trigger btn text-dark text-hover ms-auto p-0 me-4"
                        title="{{ __('Share channel') }}"
                        data-route="{{ route('youtube.profile.share') }}">
                    <i class="fa fa-share"></i>
                </button>
                <a href="https://www.youtube.com/{{ $profile->custom_url ?? ( 'channel/' . $profile->youtube_id ) }}"
                   class="text-dark text-hover"
                   title="{{ __('Open profile on YouTube') }}"
                   target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>
        <div class="d-flex flex-wrap w-100 align-items-center mt-4 pt-2">
            <div class="profile-picture-wrapper d-flex align-items-start mb-2 pe-md-3 col-12 flex-md-0">
                <div class="profile-picture-gradient m-auto">
                    <img class="profile-picture mx-auto" src="{{ $profile->thumbnail_url ?? '' }}" alt=" ">
                </div>
            </div>
            <div class="flex-1 d-flex mb-2 ps-3 w-50 min-w-50 d-flex flex-column align-items-start position-relative">
                <div class="profile-url col-12 d-flex position-absolute left-0 top-0 pt-1 hide-for-medium-up">
                    <button type="button"
                            class="share-trigger btn text-dark text-hover ms-auto p-0 me-4"
                            title="{{ __('Share channel') }}"
                            data-route="{{ route('youtube.profile.share') }}">
                        <i class="fa fa-share"></i>
                    </button>
                    <a href="https://www.youtube.com/{{ $profile->custom_url ?? ( 'channel/' . $profile->youtube_id ) }}"
                       class="text-dark text-hover"
                       title="{{ __('Open profile on YouTube') }}"
                       target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <h1 class="profile-username">
                    {{ $profile->title }}
                </h1>
                <div class="d-flex text-secondary mt-2">
                    @if($profile->custom_url)
                        <div class="profile-name">
                            {{ $profile->custom_url }}
                        </div>
                        <div class="mx-2">&#x2022;</div>
                    @endif
                    <div class="profile-subscriber-count text-nowrap">
                        {{ __(countString($profile->subscriber_count) . ' subscribers') }}
                    </div>
                    <div class="mx-2">&#x2022;</div>
                    <div class="text-nowrap">{{ __($profile->video_count . ' videos') }}</div>
                </div>
                <div class="profile-description-wrapper text-hover mt-2"
                     data-bs-target="#profileDetailsModal"
                     data-bs-toggle="modal">
                    @if($profile->description)
                        <div class="profile-description">
                            {{ $profile->description }}
                        </div>
                    @endif
                    <div class="profile-description-more">
                        {{ __('...more') }}
                    </div>
                </div>
                <a href="https://www.youtube.com/{{ $profile->custom_url ?? ( 'channel/' . $profile->youtube_id ) }}?sub_confirmation=1"
                   target="_blank"
                   class="btn btn-youtube mt-3">
                    {{ __('Subscribe') }}
                </a>
            </div>
        </div>
    </div>

    @include('elements.modal', [
        'id' => 'profileDetailsModal',
        'title' => __('About'),
        'headerClass' => 'border-0',
        'class' => 'centered',
        'bodyClass' => 'p-0',
        'body' => view('youtube.profile_details', ['profile' => $profile])
    ])
@endif