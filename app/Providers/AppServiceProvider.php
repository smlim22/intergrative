<?php

namespace App\Providers;
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
    protected $listen = [
        \App\Events\PaymentCompleted::class => [
            \App\Listeners\GenerateInvoice::class,
            \App\Listeners\EmailInvoice::class,
            \App\Listeners\WhatsAppBookingMessage::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });
    }
}
