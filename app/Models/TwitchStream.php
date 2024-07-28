<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwitchStream extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getDurationAttribute()
    {
        $hours = (int) (\Carbon\Carbon::parse($this->started_at)->diffInHours(now()) + 2);
        $minutes = (int) \Carbon\Carbon::parse($this->started_at)->diffInMinutes(now()) - (int) \Carbon\Carbon::parse($this->started_at)->diffInHours(now()) * 60;
        $seconds = (int) \Carbon\Carbon::parse($this->started_at)->diffInSeconds(now()) - (int) \Carbon\Carbon::parse($this->started_at)->diffInMinutes(now()) * 60;
        return str_pad($hours, 2, "0", STR_PAD_LEFT) . ':' . str_pad($minutes, 2, "0", STR_PAD_LEFT). ':' . str_pad($seconds, 2, "0", STR_PAD_LEFT);
    }

    public function getDurationSecondsAttribute()
    {
        return (int) \Carbon\Carbon::parse($this->started_at)->diffInSeconds(now()) + 2 * 60 * 60;
    }
}
