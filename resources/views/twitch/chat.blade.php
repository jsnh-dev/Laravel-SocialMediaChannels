<div id="liveChatParent"
     class="flex-0 p-0 transition border-start border-secondary-translucent"
     @if(session()->has('twitch.chat') && !session('twitch.chat'))
        style="margin-right: -340px;"
    @endif
>
    <iframe id="liveChat"
            src="https://www.twitch.tv/embed/{{ env('TWITCH_LOGIN') }}/chat?parent=localhost{{ session('darkmode') ? '&darkpopout' : '' }}"
            sandbox="allow-modals allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-storage-access-by-user-activation"
            height="100%"
            width="100%">
    </iframe>
</div>

<div class="twitch-chat-placeholder ms-auto"
     @if(session()->has('twitch.chat') && !session('twitch.chat'))
        style="margin-right: -340px;"
     @endif
></div>