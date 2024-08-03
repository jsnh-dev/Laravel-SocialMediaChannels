<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubePlaylist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(YoutubePlaylistItem::class, 'youtube_playlist_id', 'youtube_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function videos(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            YoutubeVideo::class,
            YoutubePlaylistItem::class,
            'youtube_playlist_id',
            'youtube_id',
            'youtube_id',
            'youtube_video_id',
        )->orderBy('position');
    }
}
