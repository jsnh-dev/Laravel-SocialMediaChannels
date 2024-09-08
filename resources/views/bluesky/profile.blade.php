@if(isset($profile) && $profile)
    <div class="profile-content flex-1 d-flex flex-column border border-secondary-translucent border-top-0 mx-2">

        <div class="profile-pictures-wrapper d-flex flex-wrap w-100 align-items-center">

            {{-- Profile banner --}}
            <div class="profile-banner-wrapper d-flex">
                <img class="profile-banner w-100" src="{{ $profile->banner ?? '' }}">
            </div>

            {{-- Profile contact for medium up --}}
            <div class="profile-contact col-12 p-3 d-flex align-items-center hide-for-small-down">
                <button type="button"
                        class="share-trigger btn profile-message-button text-dark text-decoration-none ms-auto me-2 px-3 py-2 text-hover"
                        title="{{ __('Share') }}"
                        data-route="{{ route('bluesky.profile.share') }}">
                    <i class="fa-regular fa-paper-plane"></i>
                </button>

                <a href="https://bsky.app/profile/{{ $profile->handle }}"
                   class="profile-message-button text-dark text-decoration-none me-2 px-3 py-2 text-hover"
                   target="_blank"
                   title="{{ __('Send message') }}"
                   type="button">
                    <i class="fa fa-envelope"></i>
                </a>

                <a href="https://bsky.app/profile/{{ $profile->handle }}"
                   class="profile-follow-button text-dark text-decoration-none me-2 px-3 py-2 border border-secondary-translucent text-hover"
                   target="_blank"
                   type="button">
                    {{ __('Follow') }}
                </a>

                <a href="https://bsky.app/profile/{{ $profile->handle }}"
                   class="text-dark text-hover p-2 ps-3"
                   title="{{ __('Open profile on X') }}"
                   target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>

            {{-- Profile picture --}}
            <div class="profile-picture-wrapper">
                <img class="profile-picture mx-auto flex-1 border" src="{{ $profile->avatar ?? '' }}">
            </div>

        </div>

        <div class="d-flex flex-column position-relative mt-2 p-3">
            <div class="d-flex flex-wrap flex-row-reverse">
                {{-- Profile contact --}}
                <div class="profile-contact ms-auto d-flex align-items-center hide-for-medium-up">
                    <button type="button"
                            class="share-trigger btn profile-message-button text-dark text-decoration-none ms-auto me-2 px-3 py-2 text-hover"
                            title="{{ __('Share') }}"
                            data-route="{{ route('bluesky.profile.share') }}">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>

                    <a href="https://bsky.app/profile/{{ $profile->handle }}"
                       class="profile-message-button text-dark text-decoration-none ms-auto me-2 px-3 py-2 text-hover"
                       target="_blank"
                       title="{{ __('Send message') }}"
                       type="button">
                        <i class="fa fa-envelope"></i>
                    </a>

                    <a href="https://bsky.app/profile/{{ $profile->handle }}"
                       class="profile-follow-button text-dark text-decoration-none me-2 px-3 py-2 border border-secondary-translucent text-hover"
                       target="_blank"
                       type="button">
                        {{ __('Follow') }}
                    </a>

                    <a href="https://bsky.app/profile/{{ $profile->handle }}"
                       class="text-dark text-hover p-2 ps-3"
                       title="{{ __('Open profile on X') }}"
                       target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>

                <h1 class="me-auto">{{ $profile->display_name }}</h1>
            </div>

            <div class="text-secondary mb-3">
                {{ '@' . $profile->handle }}
            </div>

            <div class="d-flex mb-3">
                <span class="me-1">{{ $profile->followers_count }}</span>
                <span class="text-secondary me-3">{{ __('Followers')  }}</span>
                <span class="me-1">{{ $profile->follows_count }}</span>
                <span class="text-secondary me-3">{{ __('Following')  }}</span>
                <span class="me-1">{{ $profile->posts_count }}</span>
                <span class="text-secondary me-3">{{ __('Posts')  }}</span>
            </div>

            @if($profile->description)
                <div class="mb-3">
                    {!! textToHtml($profile->description) !!}
                </div>
            @endif

            <div class="d-flex">

                <div class="text-secondary me-3">
                    <i class="fa fa-calendar-days me-1"></i>
                    {{ __('Joined ' . \Carbon\Carbon::parse($profile->created_at)->format('F Y')) }}
                </div>

            </div>
        </div>
    </div>
@endif