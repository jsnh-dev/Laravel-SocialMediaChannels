@if(isset($replies) && $replies)
    @foreach($replies as $reply)
        <div class="post-details-comment font-size-08rem mt-3">
            <div class="post-details-comment-text d-flex mb-1">
                <h1 class="font-size-08rem font-weight-900 float-start me-2">{{ $reply->instagramUser->username }}</h1>
                <div class="line-height-1-2">{{ $reply->text }}</div>
            </div>
            <div class="post-details-comment-metrics d-flex">
                <div class="post-details-comment-time-ago me-3">
                    {{ timeAgo($reply->timestamp) }}
                </div>
                <div class="post-details-comment-likes me-3">
                    {{ __($reply->like_count . ' likes') }}
                </div>
            </div>
        </div>
    @endforeach
@endif