<div class="d-flex flex-wrap-reverse h-100">
    <div class="col-12 col-md-6 d-flex">
        <div class="my-3 mx-auto me-md-3 me-lg-3 pe-lg-3 my-md-auto">
            <div class="card d-flex flex-column align-items-start p-3 mw-450px">
                <h2 class="profile-name font-size-175rem mb-2">
                    {{ $profile->username }}
                </h2>
                <div class="profile-headline text-secondary font-weight-900">
                    {{ $profile->name }}
                </div>
                @if($profile->biography)
                    <div class="profile-description text-vertical-overflow-ellipsis-3 text-secondary mb-3">
                        {!! textToHtml($profile->biography) !!}
                    </div>
                @endif
                <h2 class="d-flex align-items-center align-self-end">
                    <a class="btn btn-body font-weight-900" href="{{ route('instagram') }}">
                        <div class="font-size-125rem m-2">
                            <i class="fa-brands fa-instagram"></i>
                            <span class="mx-2">{{ __('View Instagram profile') }}</span>
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 d-flex">
        <div class="m-auto ms-md-0 ms-lg-3 my-3">

            <div class="profile-picture-gradient m-3">
                <img class="profile-picture mx-auto" src="{{ $profile->profile_picture_url ?? '' }}">
            </div>
        </div>
    </div>
</div>