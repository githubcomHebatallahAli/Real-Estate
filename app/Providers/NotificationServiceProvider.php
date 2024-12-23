<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use App\Notifications\Channels\VonageChannel;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // تسجيل Vonage Client باستخدام بيانات الاعتماد الصحيحة
        $this->app->singleton('vonage', function () {
            $credentials = new Basic(
                env('VONAGE_API_KEY'),
                env('VONAGE_API_SECRET')
            );

            return new Client($credentials);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // تسجيل قناة Vonage في نظام الإشعارات
        $this->app->make(ChannelManager::class)->extend('vonage', function ($app) {
            return new VonageChannel($app->make('vonage'));
        });
    }
}
