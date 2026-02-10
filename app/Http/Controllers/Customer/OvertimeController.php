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
    // HANYA yang on_progress yang bisa ditemukan
    $rental = Rental::where('user_id', auth()->id())
                    ->where('status', 'on_progress')
                    ->findOrFail($id);

    // Mencegah duplikasi: Jika sudah ada overtime (apapun statusnya selain rejected)
    $existingOt = Overtime::where('rental_id', $id)
                          ->whereIn('status', ['pending', 'approved'])
                          ->first();

    if ($existingOt) {
        return back()->with('error', 'Lembur sedang diproses atau sudah berjalan.');
    }

    Overtime::create([
        'rental_id' => $rental->id,
        'extra_hours' => 0,
        'price' => 0,
        'status' => 'pending'
    ]);

    return back()->with('success', 'Pengajuan lembur terkirim ke Admin.');
}


    public function cancel($id)
{
    $overtime = Overtime::where('id',$id)
    ->where('status','pending')
    ->whereHas('rental', fn($q) =>
        $q->where('user_id', auth()->id())
    )
    ->firstOrFail();


    $overtime->delete();

    return back()->with('success','Overtime berhasil dibatalkan');
}

public function stop($id)
{
    $ot = Overtime::findOrFail($id);
    
    // Pastikan started_at tidak null
    if (!$ot->started_at) {
        return back()->with('error', 'Waktu mulai tidak tercatat.');
    }

    $ot->ended_at = now();
    
    // Gunakan Carbon untuk hitung selisih detik agar presisi
    $start = \Carbon\Carbon::parse($ot->started_at);
    $end = \Carbon\Carbon::parse($ot->ended_at);
    
    $totalSeconds = $start->diffInSeconds($end);
    
    // Hitung extra_hours (untuk disimpan di db sebagai jam desimal)
    $ot->extra_hours = $totalSeconds / 3600; 

    // Hitung Harga: (Detik / 3600) * HargaPerJam
    $totalPrice = ($totalSeconds / 3600) * $ot->price_per_hour;

    $ot->update([
        'price' => max(0, $totalPrice), // max(0,...) memastikan tidak negatif
        'status' => 'completed',
        'ended_at' => $ot->ended_at,
        'extra_hours' => $ot->extra_hours
    ]);

    return back()->with('success', 'Overtime berhasil dihentikan.');
}

public function getStatus($id)
{
    $ot = Overtime::select('status')->findOrFail($id);
    return response()->json(['status' => $ot->status]);
}

}
