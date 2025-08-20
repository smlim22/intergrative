<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'description',
        'hourly_rate', 'half_day_rate', 'full_day_rate', 'per_use_rate'
    ];
}
