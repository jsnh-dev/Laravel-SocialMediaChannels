@if(isset($profile) && $profile)
    <div class="flex-1 d-flex flex-column position-relative">
        <div class="profile-url col-12 d-flex position-absolute left-0 top-075 hide-for-small-down">
            <button type="button"
                    class="share-trigger btn text-dark text-hover ms-auto p-0 me-4"
                    title="{{ __('Share') }}"
                    data-route="{{ route('instagram.profile.share') }}">
                <i class="fa-regular fa-paper-plane"></i>
            </button>
            <a href="https://www.instagram.com/{{ $profile->username }}/"
               class="text-dark text-hover"
               title="{{ __('Open profile on Instagram') }}"
               target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>
        <div class="d-flex flex-wrap w-100 align-items-center">
            <div class="profile-picture-wrapper d-flex align-items-start mb-4 p-0 pe-md-3 w-50 mx-auto mx-md-0">
                <div class="profile-picture-gradient {{ $profile->has_stories ? 'has-stories' : '' }} ms-md-auto flex-1 opacity-0"
                    @if($profile->has_stories)
                        data-bs-toggle="modal"
                        data-bs-target="#storyModal"
                    @endif
                >
                    <img class="profile-picture mx-auto" src="{{ $profile->profile_picture_url ?? '' }}">
                </div>
            </div>
            <div class="flex-1 d-flex mb-4 ps-3 w-50 min-w-50 d-flex flex-column align-items-start position-relative">
                <div class="profile-url col-12 d-flex position-absolute left-0 top-0 pt-1 hide-for-medium-up">
                    <button type="button"
                            class="share-trigger btn text-dark text-hover ms-auto p-0 me-4"
                            title="{{ __('Share') }}"
                            data-route="{{ route('instagram.profile.share') }}">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                    <a href="https://www.instagram.com/{{ $profile->username }}/"
                       class="text-dark text-hover"
                       title="{{ __('Open profile on Instagram') }}"
                       target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <h1 class="profile-username">
                    {{ $profile->username }}
                </h1>
                <div class="d-flex mb-3">
                    <div class="text-nowrap me-3">{{ $profile->media_count . ' ' . __('posts') }}</div>
                    <div class="text-nowrap me-3">{{ $profile->followers_count . ' ' . __('followers') }}</div>
                    <div class="text-nowrap me-3">{{ $profile->follows_count . ' ' . __('following') }}</div>
                </div>
                @if($profile->name)
                    <h2 class="profile-name font-size-1rem font-weight-900 text-secondary">
                        {{ $profile->name }}
                    </h2>
                @endif
                @if($profile->biography)
                    <div class="profile-biography text-secondary">
                        {!! textToHtml($profile->biography) !!}
                    </div>
                @endif
                @if($profile->website)
                    <div class="profile-website text-secondary">
                        <i class="fa fa-link"></i>
                        <a href="{{ $profile->website }}" class="text-secondary text-hover" target="_blank">
                            {{ str_replace('https://', '', str_replace('http://', '', $profile->website)) }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($profile->has_stories)
        @include('elements.modal', [
            'id' => 'storyModal',
            'attribute' => 'data-route=' . route('instagram.stories'),
            'class' => 'max-height overflow-visible',
            'title' => '<img class="modal-profile-picture px-1 me-2" src="' . $profile->profile_picture_url . '">' . $profile->username,
            'titleElement' =>
                '<div>
                    <span class="story-counter">1</span> / <span class="story-counter-max">' . \App\Models\InstagramMedia::where('media_product_type', 'STORY')->count() . '</span>
                </div>
                <button type="button" class="btn border-0 story-pause ms-auto">
                    <i class="fa fa-pause me-3"></i>
                </button>',
            'bodyClass' => 'overflow-visible h-50',
            'bodyInnerClass' => 'h-100 position-relative',
            'endContent' =>
                '<div class="story-nav story-next">
                    <button type="button" class="story-nav-button btn border-0 text-white">
                        <i class="fa fa-chevron-right fa-2x"></i>
                    </button>
                </div>
                <div class="story-nav story-previous d-none">
                    <button type="button" class="story-nav-button btn border-0 text-white">
                        <i class="fa fa-chevron-left fa-2x"></i>
                    </button>
                </div>'
        ])
    @endif
@endif