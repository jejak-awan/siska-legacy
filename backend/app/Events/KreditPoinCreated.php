<?php

namespace App\Events;

use App\Models\KreditPoin;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KreditPoinCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $kreditPoin;

    /**
     * Create a new event instance.
     */
    public function __construct(KreditPoin $kreditPoin)
    {
        $this->kreditPoin = $kreditPoin->load(['siswa.user', 'kategori', 'pelapor']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('kredit-poin'),
            new PrivateChannel('admin-dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'kredit-poin.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'kreditPoin' => $this->kreditPoin,
            'message' => 'Kredit poin baru telah ditambahkan',
            'timestamp' => now()->toISOString(),
        ];
    }
}
