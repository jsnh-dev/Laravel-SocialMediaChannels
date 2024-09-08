@if(isset($replies) && $replies)
    @foreach($replies as $reply)
        <div class="post-details-comment font-size-1rem mt-3">
            <div class="post-details-comment-text d-flex mb-1">
                <div class="w-100 d-flex">
                    <img class="modal-profile-picture px-1 me-2" src="{{ $reply->author_avatar }}">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <div class="me-2">
                                {{ $reply->author_display_name }}
                            </div>
                            <div class="text-secondary me-2">
                                {{ '@' . $reply->author_handle }}
                            </div>
                            <div class="text-secondary me-2">
                                ·
                            </div>
                            <div class="text-secondary">
                                {{ timeAgo($reply->created_at) }}
                            </div>
                        </div>
                        <div class="line-height-1-2 mb-3">
                            {!! textToHtml($reply->text) !!}
                        </div>
                        <div class="post-details-comment-metrics d-flex">
                            <div class="post-reply-count text-nowrap me-4">
                                <i class="fa-regular fa-message me-2"></i>{{ countString($reply->reply_count) }}
                            </div>
                            <div class="post-repost-count text-nowrap me-4">
                                <i class="fa fa-retweet me-2"></i>{{ countString($reply->repost_count) }}
                            </div>
                            <div class="post-like-count text-nowrap me-4">
                                <i class="fa-regular fa-heart me-2"></i>{{ countString($reply->like_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif