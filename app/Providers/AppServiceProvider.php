<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Api\AuthService;
use Illuminate\Auth\Access\Response;
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
        Gate::define('login', function(?User $user = null) {
            if (app(AuthService::class)->isCurrentUserFullFledged()) {
                return Response::deny('You\'re already logged in!');
            }

            return Response::allow();
        });

        Gate::define('register', function(?User $user = null) {
            if (app(AuthService::class)->isCurrentUserFullFledged()) {
                return Response::deny('You\'re already aboard!');
            }

            return Response::allow();
        });
    }
}
