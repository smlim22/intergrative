<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $pdfPath = storage_path("app/public/invoices/gantt_New.pdf");

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
