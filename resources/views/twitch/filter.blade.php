<div class="videos-profile-wrapper w-100">
    @include('twitch.profile', [
        'tab' => 'videos',
        'stream' => \App\Models\TwitchStream::first() ?? null,
        'profile' => \App\Models\TwitchProfile::first() ?? null,
    ])

    <form class="videos-filter-form row w-100 m-0 mb-3 pb-2 g-3">
        <div class="col-auto d-flex align-self-center">
            <label for="type" class="col-form-label text-nowrap me-3">{{ __('Filter by') }}</label>
            <select name="type" class="form-select">
                <option value="archive" {{ session('twitch.videos.filter.type') == 'archive' ? 'selected' : ''  }}>{{ __('Past Broadcasts') }}</option>
                <option value="highlight" {{ session('twitch.videos.filter.type') == 'highlight' ? 'selected' : ''  }}>{{ __('Highlights') }}</option>
                <option value="clip" {{ session('twitch.videos.filter.type') == 'clip' ? 'selected' : ''  }}>{{ __('Clip') }}</option>
                <option value="upload" {{ session('twitch.videos.filter.type') == 'upload' ? 'selected' : ''  }}>{{ __('Upload') }}</option>
            </select>
        </div>

        @if(session('twitch.videos.filter.type') == 'clip')
            <div class="col-auto d-flex align-self-center">
                <select name="type_top" class="form-select">
                    <option value="top_24h" {{ session('twitch.videos.filter.type_top') == 'top_24h' ? 'selected' : ''  }}>{{ __('Top 24H') }}</option>
                    <option value="top_7d" {{ session('twitch.videos.filter.type_top') == 'top_7d' ? 'selected' : ''  }}>{{ __('Top 7D') }}</option>
                    <option value="top_30d" {{ session('twitch.videos.filter.type_top') == 'top_30d' ? 'selected' : ''  }}>{{ __('Top 30D') }}</option>
                    <option value="top_all" {{ session('twitch.videos.filter.type_top') == 'top_all' ? 'selected' : ''  }}>{{ __('Top All') }}</option>
                </select>
            </div>
        @endif

        <div class="col-auto d-flex align-self-center">
            <input type="text" class="form-control" name="search" value="{{ session('twitch.videos.filter.search') }}" placeholder="{{ __('Search for videos...') }}">
        </div>
    </form>
</div>