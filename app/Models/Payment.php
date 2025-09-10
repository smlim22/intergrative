<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'id',
        'amount',
        'currency',
        'payment_status',
        'invoice_path',
    ];

    // Relationships
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
