<?php
/**
 * Author : Adrean Goh
 */
namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Services\TwilioService;

class WhatsAppBookingMessage
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function handle(PaymentCompleted $event)
    {
        $user = $event->payment->user;
        if(empty($user->phone_number)){
            return;
        }

        // Build booking details message
        $messageBody = "✅ Booking Successful!\n\n"
            . "Facility: {$event->payment->facility->name}\n"
            . "Date/Time: {$event->payment->reservation_time}\n"
            . "Amount Paid: RM " . number_format($event->payment->amount, 2) . "\n\n"
            . "Thank you, {$user->name}!";

        $this->twilio->sendWhatsAppMessage(
            $user->phone,
            $messageBody
        );
    }
}
