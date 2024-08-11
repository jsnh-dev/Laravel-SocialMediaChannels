<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return bool
     */
    public function getHasStoriesAttribute(): bool
    {
        return InstagramMedia::where('media_product_type', 'STORY')->count()
            ? true
            : false;
    }
}
