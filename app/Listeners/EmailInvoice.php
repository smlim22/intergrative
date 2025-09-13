<?php
/**
 * Author : Adrean Goh
 */
namespace App\Listeners;

use App\Events\PaymentCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\InvoiceMail;
use App\Http\Controllers\DompdfController;
class EmailInvoice
{
    public function handle(PaymentCompleted $event)
    {
        $payment = $event->payment;
       
        $dompdfController = new DompdfController();
        $dompdfController->generateInvoice($payment);
        
        
        $fileName = "invoices/invoice_{$payment->id}.pdf";
        $filePath = Storage::disk('public')->path($fileName);

        if (!Storage::disk('public')->exists($fileName)) {
            \Log::error("Invoice PDF missing for Payment ID: {$payment->id}");
        return; // or throw exception
        }
        $userEmail = $event->payment->reservation->user->email?? null;

        Mail::send([], [], function ($message) use ($event, $filePath,$userEmail) {
            $message->to($userEmail)
                    ->subject("Your Invoice")
                    ->attach($filePath, [
                        'as' => 'Invoice.pdf',
                        'mime' => 'application/pdf',
                    ]);
        });
    }
}
