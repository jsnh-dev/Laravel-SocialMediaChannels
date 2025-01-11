@if(isset($profile) && $profile)
    <div class="owl-carousel owl-theme owl" data-loop="true" data-autoplay="true">
        @if($profile->bluesky)
            @include('home.bluesky', ['profile' => $profile->bluesky])
        @endif
        @if($profile->twitch)
            @include('home.twitch', ['profile' => $profile->twitch])
        @endif
        @if($profile->youtube)
            @include('home.youtube', ['profile' => $profile->youtube])
        @endif
        @if($profile->instagram)
            @include('home.instagram', ['profile' => $profile->instagram])
        @endif
    </div>
@endif