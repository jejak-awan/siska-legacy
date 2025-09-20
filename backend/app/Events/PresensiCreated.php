<?php

namespace App\Events;

use App\Models\Presensi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresensiCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $presensi;

    /**
     * Create a new event instance.
     */
    public function __construct(Presensi $presensi)
    {
        $this->presensi = $presensi->load(['user']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('presensi'),
            new PrivateChannel('admin-dashboard'),
            new PrivateChannel('guru-dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'presensi.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'presensi' => $this->presensi,
            'message' => 'Presensi baru telah ditambahkan',
            'timestamp' => now()->toISOString(),
        ];
    }
}
