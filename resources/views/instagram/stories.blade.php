@if(isset($stories) && $stories)
    @foreach($stories as $story)
        <div class="story-wrapper">
            <div class="story-indicator">
                <div class="story-indicator-inner"></div>
            </div>
            <div class="story-image">
                <img src="{{ $story->media_url }}">
            </div>
        </div>
    @endforeach
@endif