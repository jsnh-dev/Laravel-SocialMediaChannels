@if(isset($posts) && $posts)
    @foreach($posts as $post)
        <a href="{{ route('instagram.post', ['id' => $post->instagram_id]) }}"
           class="post-wrapper col-4 border border-2 square"
           data-target="#postModal"
           data-route="{{ route('instagram.post', ['id' => $post->instagram_id]) }}"
           data-route-comments="{{ route('instagram.post.comments', ['id' => $post->instagram_id]) }}"
           data-close-route="{{ route('instagram', ['tab' => 'posts']) }}"
           data-id="{{ $post->instagram_id }}">
            @if($post->media_product_type === 'REELS')
                <div class="post-type-indicator">
                    <i class="fa fa-clapperboard"></i>
                </div>
            @elseif($post->media_type === 'VIDEO')
                <div class="post-type-indicator">
                    <i class="fa fa-play"></i>
                </div>
            @elseif($post->media_type === 'CAROUSEL_ALBUM')
                <div class="post-type-indicator">
                    <i class="fa fa-clone"></i>
                </div>
            @endif
            <div class="post-overlay">
                <div class="post-overlay-metrics d-flex align-items-center w-100 text-center">
                    <div class="post-overlay-likes flex-1 text-white">
                        <i class="fa fa-heart"></i>
                        {{ $post->like_count }}
                    </div>
                    <div class="post-overlay-comments flex-1 text-white">
                        <i class="fa fa-comment"></i>
                        {{ $post->comments_count }}
                    </div>
                </div>
            </div>
            <div class="post-image">
                <img src="{{ $post->thumbnail_url ?? $post->media_url }}">
            </div>
        </a>
    @endforeach
@endif