<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SurveyCompletedNotification extends Notification
{
    use Queueable;

    public $survey;

    /**
     * Create a new notification instance.
     */
    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'survey_id' => $this->survey->id,
            'title' => $this->survey->title,
            'message' => 'Survey Anda "' . $this->survey->title . '" telah selesai (100%).',
            'url' => route('user.surveys.show', $this->survey->id)
        ];
    }
}
