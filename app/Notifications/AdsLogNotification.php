<?php

namespace App\Notifications;

use App\Models\AdsLog;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdsLogNotification extends Notification
{
    use Queueable;
    protected AdsLog $news;
    /**
     * Create a new notification instance.
     */
    public function __construct(AdsLog $news)
    {
        $this->news=$news;
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
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
  public function toArray(object $notifiable): array
    {
        $company_id = $this->news->company_id;
        return [
            // استخدام find بدلاً من get()
            'company_name' => \App\Models\Company::find($company_id)->name,
            'news' => $this->news->news,
        ];
    }
}
