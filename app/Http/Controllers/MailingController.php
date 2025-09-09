<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailingController extends Controller
{
public function sendInvoiceViaEmail()
{
    $pdfPath = storage_path("app/public/invoices/gantt_NewTask.pdf");

    Mail::send([], [], function ($message) use ($pdfPath) {
        $message->to("customer@example.com")
                ->subject("Your Invoice")
                ->attach($pdfPath, [
                    'as' => 'Invoice.pdf',
                    'mime' => 'application/pdf',
                ]);
    });

    return "Email sent!";
}
}