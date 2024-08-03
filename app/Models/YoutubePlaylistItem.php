<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubePlaylistItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function video(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(YoutubeVideo::class, 'youtube_id', 'youtube_video_id');
    }
}
