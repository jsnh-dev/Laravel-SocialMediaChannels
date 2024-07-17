@if(isset($profile) && $profile)
    <div class="owl-carousel owl-theme owl" data-loop="true" data-autoplay="true">
        @include('home.x', ['profile' => $profile->x])
    </div>
@endif