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
    'proof'
];

public function rental()
{
    return $this->belongsTo(Rental::class);
}

}
