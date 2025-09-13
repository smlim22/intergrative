<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DompdfController;
use App\Models\Payment;
class MailingController extends Controller
{
    public function showForm()
    {
        return view('emailTest'); // Blade form
    }

    public function sendInvoiceViaEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
        ]);

        $to = $request->input('to');
        $payment = Payment::find($request->payment_id); // if you pass payment ID
        $dompdfController = new DompdfController();
        $pdfPath = $dompdfController->generateInvoice($payment);

        Mail::mailer('gmail')->send([], [], function ($message) use ($pdfPath, $to) {
            $message->to($to)
                    ->subject("Your Invoice")
                    ->attach($pdfPath, [
                        'as' => 'Invoice.pdf',
                        'mime' => 'application/pdf',
                    ]);
        });

        return back()->with('success', "Invoice sent via Gmail to {$to}!");
    }
}
