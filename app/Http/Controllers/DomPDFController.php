<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
/**
 * Author : Adrean Goh
 */

class DompdfController extends Controller
{
    /**
     * Updated to be dynamic
     */
    public function generateInvoice(Payment $payment)
    {

        $reservation = $payment->reservation; 
        $facility = $reservation->facility;   
        $user = $reservation->user;
//Updated from old ver.
        $invoiceData = [
            'invoice_no'   => $payment->id,
            'customer'     => $user->name,
            'email'        => $user->email,
            'facility'     => $facility->name,
            'reservation'  => $reservation->reservation_date . ' ' . $reservation->start_time . ' - ' . $reservation->end_time,
            'amount'       => $payment->amount,
        ];


        $pdf = Pdf::loadView('invoices.invoice-template', $invoiceData)
                  ->setPaper('a4', 'portrait');

        // Save to storage 
        $fileName = "invoices/invoice_{$payment->id}.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());


        return Storage::disk('public')->path($fileName);
    }
public function show($id)
    {
        $payment = Payment::findOrFail($id);
        $filePath = "invoices/invoice_{$payment->id}.pdf";

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->file(Storage::disk('public')->path($filePath));
    }
}
