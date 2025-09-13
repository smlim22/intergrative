<?php

/**
 * Author : Adrean Goh
 */
namespace App\Listeners;

use App\Events\PaymentCompleted;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateInvoice
{
    public function handle(PaymentCompleted $event)
    {
    $reservation = $event->payment->reservation;
        $user=$reservation->user;

        if($user->role->name != 'public'){
            return;
        }

    $invoiceData = [
        'invoice_no' => 'INV-' . $event->payment->id,
        'customer'   => $reservation->user->name,
        'email'      => $reservation->user->email,
        'facility'   => $reservation->facility->name,
        'date'       => $reservation->reservation_date,
        'time'       => $reservation->start_time . ' - ' . $reservation->end_time,
        'amount'     => $event->payment->amount,
        'currency'   => $event->payment->currency,
        'reservation'=> $reservation, 
    ];



        $pdf = Pdf::loadView('invoices.invoice-template', $invoiceData);
        $filePath = "invoices/invoice_{$event->payment->id}.pdf";

        Storage::disk('public')->put($filePath, $pdf->output());

        $event->payment->update(['invoice_path' => $filePath]);
    }
}
