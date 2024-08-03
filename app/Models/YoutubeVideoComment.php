<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeVideoComment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(YoutubeVideo::class, 'youtube_video_id', 'youtube_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(YoutubeVideoComment::class, 'youtube_parent_id', 'youtube_id');
    }
}
