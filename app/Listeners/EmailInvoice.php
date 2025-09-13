<?php
/**
 * Author : Adrean Goh
 */
namespace App\Listeners;

use App\Events\PaymentCompleted;
use Illuminate\Support\Facades\Mail;

class EmailInvoice
{
    public function handle(PaymentCompleted $event)
    {
        $filePath = storage_path("app/public/{$event->payment->invoice_path}");

        Mail::send([], [], function ($message) use ($event, $filePath) {
            $message->to("rejectbluereturntomonke@gmail.com")//CHANGE THIS AFTER TESTING
                    ->subject("Your Invoice")
                    ->attach($filePath, [
                        'as' => 'Invoice.pdf',
                        'mime' => 'application/pdf',
                    ]);
        });
    }
}
