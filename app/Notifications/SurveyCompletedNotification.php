<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SurveyCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $survey;
    public $messageText;

    /**
     * Create a new notification instance.
     */
    public function __construct($survey, $messageText = null)
    {
        $this->survey = $survey;
        $this->messageText = $messageText ?: 'Survey Anda "' . $this->survey->title . '" telah selesai (100%).';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Survey Selesai — ' . $this->survey->title)
            ->view('emails.survey-completed', [
                'survey' => $this->survey,
                'messageText' => $this->messageText,
                'notifiable' => $notifiable,
            ]);
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
            'message' => $this->messageText,
            'url' => route('user.surveys.show', $this->survey->id)
        ];
    }
}
