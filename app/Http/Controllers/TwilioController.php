<?php
/**
 * Author : Adrean Goh
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;



class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function testForm()
    {
        return view('whatsappTest'); // Blade form for text messages
    }

    // 1. Send a plain WhatsApp text
    public function sendWhatsApp(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'body' => 'required|string',
        ]);

        try {
            $message = $this->twilio->sendWhatsAppMessage($request->to, $request->body);

            return back()->with('success', "Message sent! SID: {$message->sid}");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to send: " . $e->getMessage());
        }
    }

    // 2. Send a PDF invoice (separate function)
    public function sendInvoicePdf(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
        ]);

        try {
            $to = $request->to;

            $message = $this->twilio->sendInvoicePdf($to);

            return back()->with('success', "Invoice sent! SID: {$message->sid}");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to send invoice: " . $e->getMessage());
        }
    }
}
