@if(isset($comments) && $comments)
    @foreach($comments as $comment)
        <div class="post-details-comment font-size-1rem mb-3">
            <div class="post-details-comment-text d-flex mb-1">
                <div class="w-100 d-flex">
                    <img class="modal-profile-picture px-1 me-2" src="{{ $comment->author_avatar }}">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-wrap">
                            <div class="me-2">
                                {{ $comment->author_display_name }}
                            </div>
                            <div class="text-secondary me-2">
                                {{ '@' . $comment->author_handle }}
                                <span class="ms-2">
                                ·
                                </span>
                            </div>
                            <div class="text-secondary">
                                {{ timeAgo($comment->created_at) }}
                            </div>
                        </div>
                        <div class="line-height-1-2 mb-3">
                            {!! textToHtml($comment->text) !!}
                        </div>
                        <div class="post-details-comment-metrics d-flex">
                            <div class="post-reply-count text-nowrap me-4">
                                <i class="fa-regular fa-message me-2"></i>{{ countString($comment->reply_count) }}
                            </div>
                            <div class="post-repost-count text-nowrap me-4">
                                <i class="fa fa-retweet me-2"></i>{{ countString($comment->repost_count) }}
                            </div>
                            <div class="post-like-count text-nowrap me-4">
                                <i class="fa-regular fa-heart me-2"></i>{{ countString($comment->like_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($comment->reply_count)
                <div class="post-details-comment-replies" data-id="{{ $comment->cid }}" data-route="{{ route('bluesky.post.comment.replies', ['postId' => $postId, 'id' => $comment->cid]) }}">
                    <div class="post-details-comment-replies-actions">
                        <button type="button" class="post-details-comment-view-replies btn border-0 font-size-1rem">
                            <i class="fa fa-plus me-3"></i>{{ __('View replies') }}
                        </button>
                        <button type="button" class="post-details-comment-hide-replies btn border-0 font-size-1rem d-none">
                            <i class="fa fa-minus me-3"></i>{{ __('Hide replies') }}
                        </button>
                    </div>
                    <div class="post-details-comment-replies-wrapper mx-5 d-none">
                    </div>
                    <div class="post-details-comment-replies-load-trigger mt-3 d-none">
                        <button type="button" class="post-details-comment-view-more-replies btn border-0 font-size-1rem m-auto">
                            <i class="fa fa-plus-circle fa-2x"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@endif