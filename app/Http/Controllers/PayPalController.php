<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Events\PaymentCompleted;
use App\Models\Payment;
use App\Models\Reservation;

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

    // Capture PayPal order
    $capture = $this->paypal->captureOrder($orderId);

    // Get details from PayPal response
    $result = $capture->result;
    $amount = $result->purchase_units[0]->payments->captures[0]->amount->value;
    $currency = $result->purchase_units[0]->payments->captures[0]->amount->currency_code;
    $status = $result->status; // e.g., COMPLETED

    // 🔹 Assume user already has a reservation
    $reservation = Reservation::latest()->first(); // for testing, pick the last reservation

    // Save payment record
    $payment = Payment::create([
        'reservation_id' => $reservation->id,
        'amount' => $amount,
        'currency' => $currency,
        'payment_method' => 'paypal',
        'payment_status' => strtolower($status),
        'transaction_id' => $result->id, // PayPal's transaction ID
    ]);


    // 🔹 Fire event → Listeners will auto-generate invoice, email, WhatsApp
    event(new PaymentCompleted($payment));

    return redirect()->route('facilities.index')->with('success', 'Payment successful! Invoice sent via email & WhatsApp.');
    }

    public function cancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment cancelled.');
    }

    public function testPayment()
{
    // 🔹 Get any reservation (or create a fake one if needed)
    $reservation = Reservation::first(); // assumes you have at least one reservation in DB

    if (!$reservation) {
        return "No reservation found. Please seed/create a reservation first.";
    }

    // 🔹 Hardcode a fake payment
    $payment = Payment::create([
        'reservation_id'    => $reservation->id,
        'id' => '1',
        'amount'            => 100.00,
        'currency'          => 'USD',
        'payment_status'            => 'completed',
    ]);

    // 🔹 Fire event → Listeners auto-run
    event(new PaymentCompleted($payment));

    return "✅ Test payment created. Invoice/email/WhatsApp should be triggered.";
}

}
