<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStatsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stats;
    public $role;

    /**
     * Create a new event instance.
     */
    public function __construct(array $stats, string $role = 'admin')
    {
        $this->stats = $stats;
        $this->role = $role;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new Channel('dashboard-stats'),
        ];

        // Add role-specific channels
        switch ($this->role) {
            case 'admin':
                $channels[] = new PrivateChannel('admin-dashboard');
                break;
            case 'guru':
                $channels[] = new PrivateChannel('guru-dashboard');
                break;
            case 'siswa':
                $channels[] = new PrivateChannel('siswa-dashboard');
                break;
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'dashboard.stats.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'stats' => $this->stats,
            'role' => $this->role,
            'timestamp' => now()->toISOString(),
        ];
    }
}
