@if(isset($profile) && $profile)
    <div class="owl-carousel owl-theme owl" data-loop="true" data-autoplay="true">
        @include('home.x', ['profile' => $profile->x])
        @include('home.twitch', ['profile' => $profile->twitch])
        @include('home.youtube', ['profile' => $profile->youtube])
    </div>
@endif