<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateInfoCompanyNotification extends Notification
{
    use Queueable;
    protected Company $infoApdated;
    /**
     * Create a new notification instance.
     */
    public function __construct(company $infoApdated)
    {
        $this->infoApdated = $infoApdated;
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
        return [
            'phone' => $this->infoApdated->phone,
            'name' => $this->infoApdated->name,
            'address' =>$this->infoApdated->address,
            'email' => $this->infoApdated->email,
        ];
    }
}
