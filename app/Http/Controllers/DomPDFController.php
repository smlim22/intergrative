<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DompdfController extends Controller
{
    /**
     * Generate an invoice PDF and stream it to the browser.
     */
    public function generateInvoice(Request $request)
    {
        // Example data (later you can pass from DB or form)
        $invoiceData = [
            'invoice_no'   => 'INV-2025-001',
            'customer'     => 'John Doe',
            'email'        => 'johndoe@example.com',
            'facility'     => 'Sports Hall',
            'reservation'  => '2025-09-12 10:00 - 12:00',
            'amount'       => 120.00,
        ];

        // Load Blade view and inject data
        $pdf = Pdf::loadView('invoices.invoice-template', $invoiceData)
                  ->setPaper('a4', 'portrait');

        // Save to storage (optional)
        $fileName = "invoices/invoice_{$invoiceData['invoice_no']}.pdf";
        $pdf->save(storage_path("app/public/{$fileName}"));

        // Stream to browser
        return $pdf->stream("invoice_{$invoiceData['invoice_no']}.pdf");
    }
}
