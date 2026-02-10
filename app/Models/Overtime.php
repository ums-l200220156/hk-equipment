<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
   protected $fillable = [
        'rental_id',
        'extra_hours',
        'price',
        'status',
        'payment_status',
        'payment_method',
        'proof',
        'price_per_hour',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];


public function rental()
{
    return $this->belongsTo(Rental::class);
}

}
