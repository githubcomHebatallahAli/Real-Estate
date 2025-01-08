<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Broker;
use App\Models\Chalet;
use App\Policies\ChaletPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        Chalet::class => ChaletPolicy::class,

    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // $this->registerPolicies();

        // // تأكد من إضافة تعريف Gate للسياسة
        // Gate::define('updateSale', [ChaletPolicy::class, 'updateSale']);
    }

}
