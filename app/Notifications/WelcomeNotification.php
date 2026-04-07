<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat Datang di SurveyCenter!')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Selamat datang di SurveyCenter! Akun Anda telah berhasil dibuat dan diverifikasi.')
            ->line('Dengan SurveyCenter, Anda bisa:')
            ->line('• Membuat dan mengelola survei profesional')
            ->line('• Menganalisis data responden secara real-time')
            ->line('• Mengekspor hasil survei ke PDF')
            ->action('Masuk ke Dashboard', route('user.dashboard'))
            ->line('Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim kami.')
            ->salutation('Salam, Tim SurveyCenter');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Selamat Datang!',
            'message' => 'Akun Anda telah berhasil dibuat. Selamat datang di SurveyCenter!',
            'url' => route('user.dashboard'),
        ];
    }
}
