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
use Carbon\Carbon;
class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }


public function checkout(Request $request)
{
    $request->validate([
        'facility_id' => 'required|exists:facilities,id',
        'reservation_date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required',
    ]);

    $user = auth()->user();

    // Create reservation first
    $reservation = Reservation::create([
        'facility_id' => $request->facility_id,
        'user_id' => $user->id,
        'reservation_date' => $request->reservation_date,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'status' => 'pending',
    ]);
    //swapped from using Carbon to pure php to fix error
$startTime = $reservation->start_time; 
$endTime = $reservation->end_time;     
$hourlyRate = $reservation->facility->hourly_rate;

// Conversion 
list($startHour, $startMin) = explode(':', $startTime);
list($endHour, $endMin) = explode(':', $endTime);

$startMinutes = ($startHour * 60) + $startMin;
$endMinutes = ($endHour * 60) + $endMin;

// Calculate difference
$totalMinutes = $endMinutes - $startMinutes;
if ($totalMinutes < 60) {
    $totalMinutes = 60; // minimum 1 hour
}
$totalHours = $totalMinutes / 60;

$totalHoursRounded = ceil($totalHours);


    $amount = $hourlyRate * $totalHoursRounded;
    $order = $this->paypal->createOrder($amount, 'MYR');
    foreach ($order->result->links as $link) {
        if ($link->rel === 'approve') {
            session([
                'reservation_id' => $reservation->id,
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

   
    $capture = $this->paypal->captureOrder($orderId);
    $result = $capture->result;


    $paypalAmount   = $result->purchase_units[0]->payments->captures[0]->amount->value;
    $paypalCurrency = $result->purchase_units[0]->payments->captures[0]->amount->currency_code;
    $status         = $result->status;

    $reservationId = session('reservation_id');
    $expectedAmount = session('amount');
    $expectedCurrency = session('currency', 'USD');

    $reservation = Reservation::findOrFail($reservationId);

    if (round($paypalAmount, 2) != round($expectedAmount, 2) || $paypalCurrency != $expectedCurrency) {
        return redirect()->route('facilities.index')->with('error', 'Payment mismatch detected. Please contact support.');
    }

    $payment = Payment::create([
        'reservation_id' => $reservation->id,
        'amount'         => $expectedAmount,
        'currency'       => $expectedCurrency,
        'payment_method' => 'paypal',
        'payment_status' => strtolower($status),
        'transaction_id' => $result->id, // PayPal’s transaction ID
    ]);


    $reservation->status = 'paid';
    $reservation->save();

//listner event
    event(new PaymentCompleted($payment));

    session()->forget(['reservation_id', 'amount', 'currency']);
    return view('payment/payComplete', compact('payment'));
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

    $user = auth()->user();

    if ($user->role->name === 'public') {
        return redirect()->route('checkout')->with('error', 'This option is only for Admins/Students.');
    }

    /*
$reservationId = $request->input('reservation_id');
    $reservation = Reservation::findOrFail($reservationId);
*/
    $reservation = Reservation::create([
        'facility_id' => $request->facility_id,
        'user_id' => $user->id,
        'reservation_date' => $request->reservation_date,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'status' => 'confirmed',
    ]);

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