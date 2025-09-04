<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'paypal_payment_id',
        'amount',
        'currency',
        'status'
    ];

    // Relationships
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
