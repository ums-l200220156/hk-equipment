<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'category',
        'description',
        'year',
        'brand',
        'price_per_hour',
        'status',
        'image',
        'maintenance_end_at', 
    ];

    protected $casts = [
        'maintenance_end_at' => 'date',
    ];


    public function rentals()
    {
    return $this->hasMany(Rental::class);
    }

}
