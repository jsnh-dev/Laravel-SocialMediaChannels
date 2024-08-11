@if(isset($reels) && $reels)
    @foreach($reels as $reel)
        <a href="{{ route('instagram.reel', ['id' => $reel->instagram_id]) }}"
           class="post-wrapper col-3 border border-2 format9x16 object-fit-cover"
           data-target="#postModal"
           data-route="{{ route('instagram.reel', ['id' => $reel->instagram_id]) }}"
           data-route-comments="{{ route('instagram.post.comments', ['id' => $reel->instagram_id]) }}"
           data-close-route="{{ route('instagram', ['tab' => 'reels']) }}"
           data-id="{{ $reel->instagram_id }}">
            <div class="video-plays-indicator">
                <i class="fa fa-play"></i>
            </div>
            <div class="post-overlay">
                <div class="post-overlay-metrics d-flex align-items-center w-100 text-center">
                    <div class="post-overlay-likes flex-1 text-white">
                        <i class="fa fa-heart"></i>
                        {{ $reel->like_count }}
                    </div>
                    <div class="post-overlay-comments flex-1 text-white">
                        <i class="fa fa-comment"></i>
                        {{ $reel->comments_count }}
                    </div>
                </div>
            </div>
            <div class="post-image">
                <img src="{{ $reel->thumbnail_url ?? $reel->media_url }}">
            </div>
        </a>
    @endforeach
@endif