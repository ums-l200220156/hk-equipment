<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'amount',
        'description',
        'transaction_date',
        'reference_id'
    ];

    // Casting agar tanggal otomatis terbaca sebagai objek Carbon
    protected $casts = [
        'transaction_date' => 'date',
    ];
}