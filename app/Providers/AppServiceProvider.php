<?php

namespace App\Providers;

use App\Models\Chalet;
use App\Policies\ChaletPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage_users', function($user) {
            return    Auth::guard('admin')->check();
        });

        Gate::define('updateSale', [ChaletPolicy::class, 'updateSale']);

        Gate::policy(Chalet::class, ChaletPolicy::class);
    }
}
