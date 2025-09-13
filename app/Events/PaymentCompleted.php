<?php
/**
 * Author : Adrean Goh
 */

namespace App\Events;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleted
{
    use Dispatchable, SerializesModels;
    public $payment;
    public function __construct($payment)
    {
        $this->payment = $payment;
    }
}