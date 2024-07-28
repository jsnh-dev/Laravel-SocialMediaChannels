@if(isset($profile) && $profile)
    <div class="row m-0 mt-3">
        <div class="card col-12 m-auto p-0">
            <div class="card-header">
                <h3 class="m-0">{{ __('About ' . $profile->display_name) }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12">
                        {{ __(countString($profile->followers_count) . ' followers') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {{ $profile->description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif