<?php

namespace App\Notifications\Channels;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Vonage\Client;
use Vonage\SMS\Message\SMS;
use Vonage\Client\Credentials\Basic;




class VonageChannel
{

    protected $client;

    // Constructor لتخزين العميل من Vonage
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // إرسال الرسالة
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toVonage($notifiable);


        if ($message instanceof SMS) {
            $this->client->sms()->send($message);
        }
    }



}
