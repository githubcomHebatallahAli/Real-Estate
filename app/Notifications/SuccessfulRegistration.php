<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class SuccessfulRegistration extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */


     private $otp;
     private $name;

     public function __construct($otp, $name)
     {
         $this->otp = $otp;
         $this->name = $name;
     }

    public function via(object $notifiable): array
    {
        return ['vonage'];
    }


    public function toVonage($notifiable)
    {
        $client = new Client(new Basic(config('services.vonage.vonage_api_key'), config('services.vonage.vonage_api_secret')));

        $message = $client->message()->send([
            'to' => $notifiable->phoNum,
            'from' => config('services.vonage.vonage_from'),
            'text' => 'Welcome to My App, ' . $this->name . '! Your OTP is: ' . $this->otp,
        ]);
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
