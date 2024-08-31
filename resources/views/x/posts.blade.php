<a class="twitter-timeline text-dark text-decoration-none"
   data-theme="{{ session('darkmode') ? 'dark' : 'light' }}"
   data-chrome="noheader {{--nofooter--}} noborders noscrollbar transparent"
   {{--data-show-replies="true"--}}
   data-dnt="true"
   href="https://twitter.com/{{ env('X_USERNAME') }}">
    <div id="xPostsLoader" class="infinity-loader">
        <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
    </div>
</a>