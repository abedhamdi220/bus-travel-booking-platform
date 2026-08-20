<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddTripNotification extends Notification
{
    use Queueable;
    protected Trip $trip;
    /**
     * Create a new notification instance.
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
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
        $company = Company::find('id', $this->trip->company_id)->value('name');
        return [
            'nameCompany' => $company,
            'departure_city' => $this->trip->departure_city,
            'destination' => $this->trip->destination,
            'tripType' => $this->trip->tripType,
            'dateTrip' => $this->trip->dateTrip,
            'timeTrip' => $this->trip->timeTrip
        ];
    }
}
