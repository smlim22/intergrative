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
    $payment = $event->payment;

    $reservation = $payment->reservation; 
    $user = $reservation->user;           
    $facility = $reservation->facility;   
        if(empty($user->phone_number)){
            return;
        }

        // Build booking details message
$messageBody = "✅ Booking Successful!\n\n"
        . "Facility: {$facility->name}\n"
        . "Date/Time: {$reservation->reservation_date} {$reservation->start_time} - {$reservation->end_time}\n"
        . "Amount Paid: RM " . number_format($payment->amount, 2) . "\n\n"
        . "Thank you, {$user->name}!";

        $this->twilio->sendWhatsAppMessage(
            $user->phone_number,
            $messageBody
        );
    }
}
