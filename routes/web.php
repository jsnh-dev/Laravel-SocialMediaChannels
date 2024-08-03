<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/teaser', [App\Http\Controllers\HomeController::class, 'teaser'])->name('home.teaser');

Route::get('/x', [App\Http\Controllers\XController::class, 'index'])->name('x');
Route::get('/x/profile', [App\Http\Controllers\XController::class, 'profile'])->name('x.profile');
Route::get('/x/profile/share', [App\Http\Controllers\XController::class, 'shareProfile'])->name('x.profile.share');
Route::get('/x/posts', [App\Http\Controllers\XController::class, 'posts'])->name('x.posts');

Route::get('/twitch', [App\Http\Controllers\TwitchController::class, 'index'])->name('twitch');
Route::get('/twitch/stream', [App\Http\Controllers\TwitchController::class, 'stream'])->name('twitch.stream');
Route::get('/twitch/stream/share', [App\Http\Controllers\TwitchController::class, 'shareStream'])->name('twitch.stream.share');
Route::get('/twitch/schedule', [App\Http\Controllers\TwitchController::class, 'schedule'])->name('twitch.schedule');
Route::get('/twitch/event/{id}', [App\Http\Controllers\TwitchController::class, 'event'])->name('twitch.event');
Route::get('/twitch/event/{id}/share', [App\Http\Controllers\TwitchController::class, 'shareEvent'])->name('twitch.event.share');
Route::get('/twitch/videos', [App\Http\Controllers\TwitchController::class, 'videos'])->name('twitch.videos');
Route::get('/twitch/video/{id}', [App\Http\Controllers\TwitchController::class, 'video'])->name('twitch.video');
Route::get('/twitch/video/{id}/share', [App\Http\Controllers\TwitchController::class, 'shareVideo'])->name('twitch.video.share');
Route::post('/twitch/chat/toggle', [App\Http\Controllers\TwitchController::class, 'toggleChat'])->name('twitch.chat.toggle');
Route::post('/twitch/videos/filter', [App\Http\Controllers\TwitchController::class, 'filterVideos'])->name('twitch.videos.filter');

Route::get('/youtube', [App\Http\Controllers\YoutubeController::class, 'index'])->name('youtube');
Route::get('/youtube/profile', [App\Http\Controllers\YoutubeController::class, 'profile'])->name('youtube.profile');
Route::get('/youtube/profile/share', [App\Http\Controllers\YoutubeController::class, 'shareProfile'])->name('youtube.profile.share');
Route::get('/youtube/videos', [App\Http\Controllers\YoutubeController::class, 'videos'])->name('youtube.videos');
Route::get('/youtube/video/{id}', [App\Http\Controllers\YoutubeController::class, 'video'])->name('youtube.video');
Route::get('/youtube/video/{id}/comments', [App\Http\Controllers\YoutubeController::class, 'comments'])->name('youtube.video.comments');
Route::get('/youtube/video/{videoId}/comment/{id}/replies', [App\Http\Controllers\YoutubeController::class, 'replies'])->name('youtube.video.comment.replies');
Route::get('/youtube/video/{id}/share', [App\Http\Controllers\YoutubeController::class, 'shareVideo'])->name('youtube.video.share');
Route::get('/youtube/playlists', [App\Http\Controllers\YoutubeController::class, 'playlists'])->name('youtube.playlists');
Route::get('/youtube/playlist/{id}', [App\Http\Controllers\YoutubeController::class, 'playlist'])->name('youtube.playlist');
Route::get('/youtube/playlist/{id}/share', [App\Http\Controllers\YoutubeController::class, 'sharePlaylist'])->name('youtube.playlist.share');