<?php

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

    public function sendWhatsApp(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'body' => 'required|string',
        ]);

        $message = $this->twilio->sendWhatsAppMessage(
            $request->to,
            $request->body,
            $request->input('contentSid'),
            $request->input('variables', [])
        );

        return response()->json([
            'status' => 'success',
            'sid' => $message->sid,
        ]);
    }
}
