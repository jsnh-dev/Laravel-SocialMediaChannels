<?php

namespace App\Events;

use App\Models\TwitchStream;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTwitchStream implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $stream;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->stream = TwitchStream::first();

        if ($this->stream) {
            $this->stream->duration = $this->stream->duration ?? 0;
            $this->stream->duration_seconds = $this->stream->duration_seconds ?? 0;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('twitch.stream'),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'update';
    }

    /**
     * The event's broadcast name.
     *
     * @return string[]
     */
    public function broadcastWith(): array
    {
        return ['stream' => $this->stream];
    }
}
