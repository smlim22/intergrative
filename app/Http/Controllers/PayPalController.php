<?php
/**
 * Author : Adrean Goh
 */
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


public function checkout(Request $request)
{
    $reservationId = $request->input('reservation_id');
    $reservation = Reservation::with('facility')->findOrFail($reservationId);

    // ✅ Get hourly rate from facility
    $hourlyRate = $reservation->facility->hourly_rate;

    // ✅ Calculate hours between start & end time
    $start = \Carbon\Carbon::parse($reservation->start_time);
    $end   = \Carbon\Carbon::parse($reservation->end_time);
    $hours = $end->diffInHours($start);

    if ($hours < 1) {
        $hours = 1; // minimum 1 hour
    }

    // ✅ Calculate total cost
    $amount = $hourlyRate * $hours;

    // Create PayPal order
    $order = $this->paypal->createOrder($amount, 'USD'); 
    // 👉 if you want MYR, use 'MYR' (ensure PayPal supports it for your account)

    foreach ($order->result->links as $link) {
        if ($link->rel === 'approve') {
            // Save reservation id & amount in session for later use
            session([
                'reservation_id' => $reservationId,
                'amount' => $amount,
                'currency' => 'MYR',
            ]);
            return redirect()->away($link->href);
        }
    }

    return redirect()->back()->with('error', 'Unable to create PayPal order.');
}




public function success(Request $request)
{
    $orderId = $request->query('token');

    if (!$orderId) {
        return redirect()->route('checkout')->with('error', 'Missing PayPal token.');
    }

    // ✅ Capture PayPal order
    $capture = $this->paypal->captureOrder($orderId);
    $result = $capture->result;

    // ✅ Get actual PayPal amount
    $paypalAmount   = $result->purchase_units[0]->payments->captures[0]->amount->value;
    $paypalCurrency = $result->purchase_units[0]->payments->captures[0]->amount->currency_code;
    $status         = $result->status;

    // ✅ Get expected values from session
    $reservationId = session('reservation_id');
    $expectedAmount = session('amount');
    $expectedCurrency = session('currency', 'USD');

    $reservation = Reservation::findOrFail($reservationId);

    // ✅ Verify PayPal charge matches expected
    if (round($paypalAmount, 2) != round($expectedAmount, 2) || $paypalCurrency != $expectedCurrency) {
        return redirect()->route('facilities.index')->with('error', 'Payment mismatch detected. Please contact support.');
    }

    // ✅ Save payment record
    $payment = Payment::create([
        'reservation_id' => $reservation->id,
        'amount'         => $expectedAmount,
        'currency'       => $expectedCurrency,
        'payment_method' => 'paypal',
        'payment_status' => strtolower($status),
        'transaction_id' => $result->id, // PayPal’s transaction ID
    ]);

    // ✅ Update reservation
    $reservation->status = 'paid';
    $reservation->save();

    // ✅ Fire event (send invoice/email/WhatsApp)
    event(new PaymentCompleted($payment));

    // ✅ Clear session data
    session()->forget(['reservation_id', 'amount', 'currency']);

    return redirect()->route('facilities.index')->with('success', 'Payment successful! Invoice sent via email & WhatsApp.');
}


    public function cancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment cancelled.');
    }

    public function testPayment()
{
    $reservation = Reservation::first(); // assumes you have at least one reservation in DB
    if (!$reservation) {
        return "No reservation found. Please seed/create a reservation first.";
    }

    //  Hardcoded fake payment fr test
    $payment = Payment::create([
        'reservation_id'    => $reservation->id,
        'amount'            => 100.00,
        'currency'          => 'USD',
        'payment_status'            => 'completed',
    ]);

    // 🔹 Fire event → Listeners auto-run
    event(new PaymentCompleted($payment));

    return "✅ Test payment created. Invoice/email/WhatsApp should be triggered.";
}

public function bookingStudentAdmin(Request $request)
{
$reservationId = $request->input('reservation_id');
    $reservation = Reservation::findOrFail($reservationId);

    $user = auth()->user();

    if ($user->role->name === 'public') {
        return redirect()->route('checkout')->with('error', 'This option is only for Admins/Students.');
    }


    $reservation->status = 'confirmed';
    $reservation->save();

    /* suggestion from AI
    $payment = Payment::create([
        'reservation_id' => $reservation->id,
        'amount' => 0,
        'currency' => 'MYR',
        'payment_method' => 'free',
        'payment_status' => 'completed',
        'transaction_id' => 'FREE-' . uniqid(),
    ]);

    // Fire event → invoice/email/WhatsApp
    event(new PaymentCompleted($payment));
*/
    return redirect()->route('facilities.index')->with('success', 'Booking confirmed without payment.');
}

}