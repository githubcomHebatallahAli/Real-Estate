<?php

namespace App\Notifications\Channels;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Vonage\Client;
use Vonage\SMS\Message\SMS;


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
        // الحصول على الرسالة من طريقة toVonage في الإشعار
        $message = $notification->toVonage($notifiable);

        // التأكد من أن الرسالة هي من النوع SMS
        if ($message instanceof SMS) {
            // إرسال الرسالة عبر خدمة Vonage
            $this->client->sms()->send($message);
        }
    }



}
