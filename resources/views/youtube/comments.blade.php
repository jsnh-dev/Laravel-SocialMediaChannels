@if(isset($comments) && $comments)
    @foreach($comments as $comment)
        <div class="post-details-comment font-size-08rem mb-3">
            <div class="post-details-comment-text d-flex align-items-center mb-1">
                <img src="{{ $comment->author_profile_image_url }}" alt=" " class="post-details-comment-author-picture me-2">
                <h1 class="font-size-08rem font-weight-900 float-start me-2">{{ $comment->author_display_name }}</h1>
                <div class="post-details-comment-time-ago me-3 text-secondary">
                    {{ timeAgo($comment->published_at) }}
                </div>
            </div>
            <div class="mb-2">
                {!! $comment->text_display !!}
            </div>
            <div class="post-details-comment-metrics d-flex align-items-center">
                <a class="d-flex align-items-center text-dark text-hover text-decoration-none"
                   href="https://www.youtube.com/watch?v={{ $comment->video->youtube_id }}&lc={{ $comment->youtube_id }}"
                   title="{{ __('Like') }}"
                   target="_blank">
                    <i class="fa fa-thumbs-up"></i>
                    @if($comment->like_count)
                        <div class="post-details-comment-likes text-secondary ms-2">
                            {{ $comment->like_count }}
                        </div>
                    @endif
                </a>
                <a class="d-flex align-items-center text-dark text-hover text-decoration-none ms-3"
                   href="https://www.youtube.com/watch?v={{ $comment->video->youtube_id }}&lc={{ $comment->youtube_id }}"
                   title="{{ __('Dislike') }}"
                   target="_blank">
                    <i class="fa fa-thumbs-down"></i>
                </a>
                <a class="d-flex align-items-center text-dark text-hover text-decoration-none ms-3"
                   href="https://www.youtube.com/watch?v={{ $comment->video->youtube_id }}&lc={{ $comment->youtube_id }}"
                   title="{{ __('Reply') }}"
                   target="_blank">
                    {{ __('Reply') }}
                </a>
            </div>
            @if($comment->children->count())
                <div class="post-details-comment-replies" data-id="{{ $comment->youtube_id }}" data-route="{{ route('youtube.video.comment.replies', ['videoId' => $comment->youtube_video_id, 'id' => $comment->youtube_id]) }}">
                    <div class="post-details-comment-replies-actions">
                        <button type="button" class="post-details-comment-view-replies btn border-0 font-size-08rem text-hover">
                            <i class="fa fa-plus me-3"></i>{{ $comment->children->count() > 1 ? __($comment->children->count() . ' replies') : __($comment->children->count() . ' reply') }}
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
    @if($comments->currentPage() == $comments->lastPage() && $comments->total() == 100)
        <a class="d-flex align-items-center text-dark text-hover text-decoration-none"
           href="https://www.youtube.com/watch?v={{ $comments->last()->youtube_video_id }}"
           target="_blank">
            {{ __('Read more comments on YouTube') }}
        </a>
    @endif
@endif