@if(isset($comments) && $comments)
    @foreach($comments as $comment)
        <div class="post-details-comment font-size-08rem mb-3">
            <div class="post-details-comment-text d-flex mb-1">
                <h1 class="font-size-08rem font-weight-900 float-start me-2">{{ $comment->instagramUser->username }}</h1>
                <div class="line-height-1-2">{{ $comment->text }}</div>
            </div>
            <div class="post-details-comment-metrics d-flex">
                <div class="post-details-comment-time-ago me-3">
                    {{ timeAgo($comment->timestamp) }}
                </div>
                <div class="post-details-comment-likes me-3">
                    {{ __($comment->like_count . ' likes') }}
                </div>
            </div>
            @if($comment->children->count())
                <div class="post-details-comment-replies" data-id="{{ $comment->instagram_id }}" data-route="{{ route('instagram.post.comment.replies', ['postId' => $comment->instagram_media_id, 'id' => $comment->instagram_id]) }}">
                    <div class="post-details-comment-replies-actions">
                        <button type="button" class="post-details-comment-view-replies btn border-0 font-size-08rem">
                            <i class="fa fa-plus me-3"></i>{{ __('View replies') }}
                        </button>
                        <button type="button" class="post-details-comment-hide-replies btn border-0 font-size-08rem d-none">
                            <i class="fa fa-minus me-3"></i>{{ __('Hide replies') }}
                        </button>
                    </div>
                    <div class="post-details-comment-replies-wrapper mx-5 d-none">
                    </div>
                    <div class="post-details-comment-replies-load-trigger mt-3 d-none">
                        <button type="button" class="post-details-comment-view-more-replies btn border-0 font-size-08rem m-auto">
                            <i class="fa fa-plus-circle fa-2x"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@endif