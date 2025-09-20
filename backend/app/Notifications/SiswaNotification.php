<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SiswaNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->data['subject'] ?? 'Notifikasi Siswa')
            ->line($this->data['message'] ?? '')
            ->action('Lihat Detail', url($this->data['url'] ?? '/'));
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->data['subject'] ?? 'Notifikasi Siswa',
            'body' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? '/',
        ]);
    }
}
