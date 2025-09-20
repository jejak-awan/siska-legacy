<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\WhatsAppLog;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send notification to single user
     */
    public function sendNotification(array $data): Notifikasi
    {
        $notification = Notifikasi::create([
            'user_id' => $data['user_id'],
            'judul' => $data['judul'],
            'pesan' => $data['pesan'],
            'tipe' => $data['tipe'] ?? 'info',
            'status' => 'unread',
            'data' => $data['data'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'action_text' => $data['action_text'] ?? null,
        ]);

        // Send WhatsApp notification if enabled
        if (isset($data['send_whatsapp']) && $data['send_whatsapp']) {
            $this->sendWhatsAppNotification($notification);
        }

        return $notification;
    }

    /**
     * Send notification to multiple users
     */
    public function sendBulkNotification(array $data): array
    {
        $notifications = [];
        $userIds = $data['user_ids'] ?? [];

        foreach ($userIds as $userId) {
            $notification = $this->sendNotification([
                'user_id' => $userId,
                'judul' => $data['judul'],
                'pesan' => $data['pesan'],
                'tipe' => $data['tipe'] ?? 'info',
                'data' => $data['data'] ?? null,
                'action_url' => $data['action_url'] ?? null,
                'action_text' => $data['action_text'] ?? null,
                'send_whatsapp' => $data['send_whatsapp'] ?? false,
            ]);
            
            $notifications[] = $notification;
        }

        return $notifications;
    }

    /**
     * Send notification to users by role
     */
    public function sendNotificationByRole(array $data): array
    {
        $role = $data['role'];
        $userIds = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->pluck('id')->toArray();

        return $this->sendBulkNotification([
            'user_ids' => $userIds,
            'judul' => $data['judul'],
            'pesan' => $data['pesan'],
            'tipe' => $data['tipe'] ?? 'info',
            'data' => $data['data'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'action_text' => $data['action_text'] ?? null,
            'send_whatsapp' => $data['send_whatsapp'] ?? false,
        ]);
    }

    /**
     * Send WhatsApp notification
     */
    public function sendWhatsAppNotification(Notifikasi $notification): bool
    {
        try {
            $user = $notification->user;
            if (!$user || !$user->no_telepon) {
                return false;
            }

            $message = $this->formatWhatsAppMessage($notification);
            
            // Use WhatsApp service to send message
            $result = $this->whatsappService->sendTextMessage(
                $user->no_telepon,
                $message,
                $user->id
            );

            return $result['success'];

        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage(), [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id
            ]);

            return false;
        }
    }

    /**
     * Format WhatsApp message
     */
    private function formatWhatsAppMessage(Notifikasi $notification): string
    {
        $message = "*{$notification->judul}*\n\n";
        $message .= $notification->pesan . "\n\n";
        
        if ($notification->action_url) {
            $message .= "🔗 " . $notification->action_text . "\n";
        }
        
        $message .= "\n📱 Sistem Kesiswaan";
        
        return $message;
    }


    /**
     * Mark notification as read
     */
    public function markAsRead(Notifikasi $notification): bool
    {
        return $notification->update(['status' => 'read', 'read_at' => now()]);
    }

    /**
     * Mark notification as archived
     */
    public function markAsArchived(Notifikasi $notification): bool
    {
        return $notification->update(['status' => 'archived']);
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notifikasi::where('user_id', $userId)
            ->where('status', 'unread')
            ->update(['status' => 'read', 'read_at' => now()]);
    }

    /**
     * Get notification statistics for user
     */
    public function getStatistics(int $userId): array
    {
        return [
            'total' => Notifikasi::where('user_id', $userId)->count(),
            'unread' => Notifikasi::where('user_id', $userId)->where('status', 'unread')->count(),
            'read' => Notifikasi::where('user_id', $userId)->where('status', 'read')->count(),
            'archived' => Notifikasi::where('user_id', $userId)->where('status', 'archived')->count(),
            'by_type' => Notifikasi::where('user_id', $userId)
                ->selectRaw('tipe, count(*) as count')
                ->groupBy('tipe')
                ->pluck('count', 'tipe')
                ->toArray()
        ];
    }

    /**
     * Send system notification (for internal events)
     */
    public function sendSystemNotification(string $title, string $message, string $type = 'info', array $data = []): Notifikasi
    {
        // Get admin users
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->pluck('id')->toArray();

        if (empty($adminUsers)) {
            throw new \Exception('No admin users found');
        }

        // Send to first admin user
        return $this->sendNotification([
            'user_id' => $adminUsers[0],
            'judul' => $title,
            'pesan' => $message,
            'tipe' => $type,
            'data' => $data,
            'send_whatsapp' => false
        ]);
    }

    /**
     * Clean up old notifications
     */
    public function cleanupOldNotifications(int $daysOld = 30): int
    {
        return Notifikasi::where('created_at', '<', now()->subDays($daysOld))
            ->where('status', 'archived')
            ->delete();
    }
}
