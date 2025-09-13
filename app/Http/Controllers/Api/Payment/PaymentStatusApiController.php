<?php
/**
 * Author : Adrean Goh
 */
namespace App\Http\Controllers\Api\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentStatusApiController extends Controller
{
    public function checkPayment(Request $request){
        $paymentID = $request->query('paymentID');

        if(!$paymentID){
        return response()->json(['error' => 'paymentID query parameter is required'], 400);
    }
      $payment = Payment::find($paymentID);
    if($payment){
        return response()->json([
            'payment_status' => $payment->payment_status,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
        ], 200);
    }else{
        return response()->json(['error' => 'Payment not found'], 404);
    }
    }


}
