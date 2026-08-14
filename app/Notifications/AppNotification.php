<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     * Menerima payload array dinamis untuk disimpan di database.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Hanya database untuk notif dalam aplikasi (WA sudah ada logic sendiri).
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Menyalin isi payload data mentah agar terekam ke kolom 'data' di JSON MySQL.
        return $this->data;
    }
}
