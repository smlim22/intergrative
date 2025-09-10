<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Events\PaymentCompleted;
use App\Listeners\GenerateInvoice;
use App\Listeners\EmailInvoice;
use App\Listeners\WhatsAppBookingMessage;
use Illuminate\Support\Facades\Event;
use App\Services\MailServiceInterface;
use App\Services\GmailAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            MailServiceInterface::class,
            GmailAdapter::class
        );
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });

    Event::listen(PaymentCompleted::class, GenerateInvoice::class);
    Event::listen(PaymentCompleted::class, EmailInvoice::class);
    Event::listen(PaymentCompleted::class, WhatsAppBookingMessage::class);
    }
}
