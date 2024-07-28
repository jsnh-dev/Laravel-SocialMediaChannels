<div class="d-flex flex-wrap-reverse h-100">
    <div class="col-12 col-md-6 d-flex">
        <div class="my-3 mx-auto me-md-3 me-lg-3 pe-lg-3 my-md-auto">
            <div class="card d-flex flex-column align-items-start p-3">
                <h2 class="profile-name font-size-175rem mb-2">
                    {{ $profile->display_name }}
                </h2>
                <div class="profile-description font-size-08rem mb-3">
                    {{ $profile->description }}
                </div>
                <h2 class="d-flex align-items-center align-self-end">
                    <a class="btn btn-twitch font-weight-900" href="{{ route('twitch') }}">
                        <div class="font-size-125rem m-2">
                            <i class="fa-brands fa-twitch text-white"></i>
                            <span class="mx-2">{{ __('View Twitch profile') }}</span>
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 d-flex">
        <div class="m-auto ms-md-0 ms-lg-3 my-3 d-flex">
            <div class="profile-picture-gradient m-3 align-self-center">
                <img class="profile-picture mx-auto"  src="{{ $profile->profile_image_url ?? '' }}">
            </div>
        </div>
    </div>
</div>