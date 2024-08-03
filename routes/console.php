<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:x')->hourly();

Schedule::command('app:twitch')->hourly()->runInBackground();
Schedule::command('app:twitch_stream')->everyMinute()->runInBackground();

Schedule::command('app:youtube')->hourly();
