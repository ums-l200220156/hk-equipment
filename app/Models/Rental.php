<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'equipment_id',
        'rent_date',
        'start_time',
        'duration_hours',
        'location',
        'notes',
        'status',
        'total_price',
        'payment_method',
        'payment_proof',
    ];

    /* ==========================
     | RELATIONS
     ========================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function overtime()
    {
        return $this->hasOne(Overtime::class);
    }

    /* ==========================
     | OVERTIME LOGIC
     ========================== */

    public function getOvertimeHoursAttribute()
    {
        $start = Carbon::parse($this->rent_date . ' ' . $this->start_time);
        $shouldEnd = $start->copy()->addHours($this->duration_hours);

        if (now()->lessThanOrEqualTo($shouldEnd)) {
            return 0;
        }

        return (int) ceil($shouldEnd->diffInMinutes(now()) / 60);
    }

    public function getIsOvertimeAttribute()
    {
        return $this->overtime_hours > 0;
    }

    public function getCanRequestOvertimeAttribute()
    {
        return $this->is_overtime && !$this->overtime;
    }
}
