<div class="d-flex flex-wrap h-100 position-relative">
    @if($profile->banner && getimagesize($profile->banner))
        <img src="{{ $profile->banner }}" alt=" " class="position-absolute w-100 h-100 object-fit-cover opacity-50 z-n1">
    @endif
    <div class="col-12 col-md-6 d-flex">
        <div class="m-auto me-md-0 me-lg-3">
            <img class="profile-picture my-3 mx-auto me-md-0" src="{{ $profile->avatar ?? '' }}" alt=" ">
        </div>
    </div>
    <div class="col-12 col-md-6 d-flex">
        <div class="my-3 mx-auto ms-md-3 ms-lg-3 ps-lg-3 my-md-auto">
            <div class="card d-flex flex-column align-items-start p-3 mw-450px">
                <h2 class="profile-name font-size-175rem">
                    {{ $profile->display_name }}
                </h2>
                <div class="profile-headline text-secondary mb-2">
                    {{ '@' . $profile->handle }}
                </div>
                <div class="profile-description text-vertical-overflow-ellipsis-3 mb-3">
                    {!! textToHtml($profile->description) !!}
                </div>
                <h2 class="d-flex align-items-center">
                    <a class="btn btn-primary font-weight-800" href="{{ route('bluesky') }}">
                        <div class="font-size-125rem m-2">
                            <i class="fa-brands fa-bluesky text-white"></i>
                            <span class="mx-2">{{ __('View Bluesky profile') }}</span>
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </h2>
            </div>
        </div>
    </div>
</div>