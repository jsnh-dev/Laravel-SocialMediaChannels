<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlueskyPost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getPermalinkAttribute()
    {
        return 'https://bsky.app/profile/' . env('BLUESKY_IDENTIFIER') . '/post' .  substr($this->uri, strrpos($this->uri, '/'));
    }
}
