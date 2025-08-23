<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }

    // Step 1: Create order
    public function checkout()
    {
        $order = $this->paypal->createOrder(10.00, 'USD'); // $10 test payment

        foreach ($order->result->links as $link) {
            if ($link->rel === 'approve') {
                return redirect()->away($link->href);
            }
        }

        return redirect()->back()->with('error', 'Unable to create PayPal order.');
    }

    // Step 2: Capture after PayPal approval
    public function success(Request $request)
    {
        $orderId = $request->query('token');

        if (!$orderId) {
            return redirect()->route('checkout')->with('error', 'Missing PayPal token.');
        }

        $capture = $this->paypal->captureOrder($orderId);

        return response()->json($capture);
    }

    public function cancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment cancelled.');
    }
}
