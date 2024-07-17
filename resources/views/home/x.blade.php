@if(isset($profile) && $profile)
    <div class="d-flex flex-wrap h-100 position-relative">
        @if($profile->profile_banner_url && getimagesize($profile->profile_banner_url))
            <img src="{{ $profile->profile_banner_url }}" alt=" " class="position-absolute w-100 h-100 object-fit-cover opacity-50 z-n1">
        @endif
        <div class="col-12 col-md-6 d-flex">
            <div class="m-auto me-md-0 me-lg-3">
                <img class="profile-picture my-3 mx-auto me-md-0" src="{{ $profile->profile_image_url_https ?? '' }}" alt=" ">
            </div>
        </div>
        <div class="col-12 col-md-6 d-flex">
            <div class="my-3 mx-auto ms-md-3 ms-lg-3 ps-lg-3 my-md-auto">
                <div class="card d-flex flex-column align-items-start p-3">
                    <h2 class="profile-name font-size-175rem">
                        {{ $profile->name }}
                    </h2>
                    <div class="profile-headline text-secondary mb-2">
                        {{ '@' . $profile->screen_name }}
                    </div>
                    <div class="profile-description font-size-08rem mb-3">
                        {{ $profile->description }}
                    </div>
                    <h2 class="d-flex align-items-center">
                        <a class="btn btn-primary font-weight-900" href="{{ 'x' }}">
                            <div class="font-size-125rem m-2">
                                <i class="fa-brands fa-x-twitter text-white"></i>
                                <span class="mx-2">{{ 'View X profile' }}</span>
                                <i class="fa fa-arrow-right"></i>
                            </div>
                        </a>
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endif