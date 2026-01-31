<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Overtime;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function store($id)
    {
        $rental = Rental::where('user_id', Auth::id())
            ->with('equipment')
            ->findOrFail($id);

        if ($rental->overtime_hours <= 0) {
            return back()->with('error', 'Belum ada overtime.');
        }

        if ($rental->overtime) {
            return back()->with('error', 'Overtime sudah diajukan.');
        }

        Overtime::create([
            'rental_id'   => $rental->id,
            'extra_hours' => $rental->overtime_hours,
            'price'       => $rental->overtime_hours * $rental->equipment->price_per_hour
        ]);

        return back()->with('success', 'Overtime berhasil diajukan.');
    }


    public function cancel($id)
{
    $overtime = Overtime::where('id',$id)
        ->where('status','pending')
        ->firstOrFail();

    $overtime->delete();

    return back()->with('success','Overtime berhasil dibatalkan');
}

}
