<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class TwilioController extends Controller
{
    protected $twilio;
    public function testForm()
{
    return view('whatsappTest'); // the blade file you'll create
}


    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

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
}
