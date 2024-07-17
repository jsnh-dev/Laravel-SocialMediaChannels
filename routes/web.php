<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/teaser', [App\Http\Controllers\HomeController::class, 'teaser'])->name('home.teaser');

Route::get('/x', [App\Http\Controllers\XController::class, 'index'])->name('x');
Route::get('/x/profile', [App\Http\Controllers\XController::class, 'profile'])->name('x.profile');
Route::get('/x/profile/share', [App\Http\Controllers\XController::class, 'shareProfile'])->name('x.profile.share');
Route::get('/x/posts', [App\Http\Controllers\XController::class, 'posts'])->name('x.posts');