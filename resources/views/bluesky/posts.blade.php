@if(isset($posts) && $posts)
    @foreach($posts as $post)
        <a href="{{ route('bluesky.post', ['id' => $post->cid]) }}"
           class="post-wrapper bluesky text-dark text-decoration-none text-hover card border-radius-0 bg-transparent"
           data-target="#postModal"
           data-route="{{ route('bluesky.post', ['id' => $post->cid]) }}"
           data-route-comments="{{ route('bluesky.post.comments', ['id' => $post->cid]) }}"
           data-close-route="{{ route('bluesky') }}"
           data-id="{{ $post->cid }}">
            <div class="mb-3">
                <span class="text-secondary">
                    {{ timeAgo($post->created_at) }}
                </span>
            </div>
            <p class="mb-0 text-vertical-overflow-ellipsis-5 mb-3">
                <span>
                    {!! textToHtml($post->text) !!}
                </span>
            </p>
            @if($post->media_url)
                <div class="post-image square position-relative mb-3">
                    <img class="w-100 h-100" src="{{ $post->media_url }}">
                </div>
            @endif
            <div class="post-metrics d-flex align-items-end text-secondary w-100 mt-auto">
                <div class="post-reply-count text-nowrap me-auto">
                    <i class="fa-regular fa-message me-2"></i>{{ countString($post->reply_count) }}
                </div>
                <div class="post-repost-count text-nowrap me-2">
                    <i class="fa fa-retweet mx-2"></i>{{ countString($post->repost_count) }}
                </div>
                <div class="post-like-count text-nowrap ms-auto">
                    <i class="fa-regular fa-heart me-2"></i>{{ countString($post->like_count) }}
                </div>
            </div>
        </a>
    @endforeach
@endif