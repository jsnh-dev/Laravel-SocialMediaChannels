<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:twitch')->hourly()->runInBackground();
Schedule::command('app:twitch_stream')->everyMinute()->runInBackground();

Schedule::command('app:youtube')->hourly();

Schedule::command('app:instagram')->hourly();

Schedule::command('app:bluesky')->hourly();