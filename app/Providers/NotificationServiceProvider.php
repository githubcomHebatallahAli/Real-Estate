<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use App\Notifications\Channels\VonageChannel;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('vonage', function () {
            $credentials = new Basic(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
            return new Client($credentials);
        });
    }

    public function boot()
    {
        $this->app->make(ChannelManager::class)->extend('vonage', function ($app) {
            return new VonageChannel($app->make('vonage'));
        });
    }
}
