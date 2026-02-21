<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleAdminController extends Controller
{
    // 🧠 HALAMAN UTAMA
    public function index()
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('admin.schedule.index', compact('equipment'));
    }

    // 📅 EVENT CALENDAR
    public function events(Request $request)
    {
        $query = Rental::with('equipment','user')
            ->whereIn('status', ['pending','approved','on_progress']);

        if ($request->equipment_id) {
            $query->where('equipment_id', $request->equipment_id);
        }

        return $query->get()->map(function ($r) {
            // PERBAIKAN: Kunci zona waktu ke Asia/Jakarta agar jam tidak melompat
            $start = Carbon::parse($r->rent_date.' '.$r->start_time, 'Asia/Jakarta');
            $end   = $start->copy()->addHours($r->duration_hours);

            return [
                'id' => $r->id,
                'title' => $r->equipment->name,
                'start' => $start->toIso8601String(),
                'end'   => $end->toIso8601String(),
                'backgroundColor' => match($r->status) {
                    'pending'     => '#facc15',
                    'approved'    => '#3b82f6',
                    'on_progress' => '#f97316',
                    default       => '#22c55e'
                },
                'extendedProps' => [
                    'customer' => $r->user->name,
                    'status'   => $r->status,
                ]
            ];
        });
    }

    // 🔄 DRAG & DROP + ⚠️ CEK BENTROK
    public function update(Request $request)
    {
        $rental = Rental::findOrFail($request->id);

        // PERBAIKAN: Gunakan zona waktu yang sama saat update koordinat baru
        $start = Carbon::parse($request->start, 'Asia/Jakarta');
        $end   = Carbon::parse($request->end, 'Asia/Jakarta');
        $hours = max(1, $start->diffInHours($end));

        // ⚠️ DETEKSI BENTROK
        $conflict = Rental::where('equipment_id', $rental->equipment_id)
            ->where('id', '!=', $rental->id)
            ->where(function ($q) use ($start, $end) {
                $q->whereRaw(
                    "TIMESTAMP(rent_date, start_time) < ? 
                     AND TIMESTAMP(rent_date, start_time) + INTERVAL duration_hours HOUR > ?",
                    [$end, $start]
                );
            })->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'Bentrok jadwal dengan sewa lain'
            ], 422);
        }

        $rental->update([
            'rent_date' => $start->toDateString(),
            'start_time' => $start->format('H:i'),
            'duration_hours' => $hours
        ]);

        return response()->json(['success' => true]);
    }

    // 📊 HEATMAP PEMAKAIAN 
    public function heatmap(Request $request)
    {
        $period = $request->query('period', 'all'); // Default: Semua Waktu
        $now = now();

        // 1. Definisikan closure untuk filter status & waktu
        $queryFilter = function ($query) use ($period, $now) {
            $query->whereIn('status', ['on_progress', 'completed']);

            if ($period == 'week') {
                $query->whereBetween('rent_date', [
                    $now->startOfWeek()->toDateString(), 
                    $now->endOfWeek()->toDateString()
                ]);
            } elseif ($period == 'month') {
                $query->whereMonth('rent_date', $now->month)
                    ->whereYear('rent_date', $now->year);
            } elseif ($period == 'year') {
                $query->whereYear('rent_date', $now->year);
            }
        };

        // 2. Hitung total penyewaan dalam periode tersebut sebagai pembagi (Total All)
        $totalAllRentals = Rental::where($queryFilter)->count();

        // 3. Ambil data per alat dengan filter yang sama
        return Equipment::withCount(['rentals' => $queryFilter])->get()->map(function ($e) use ($totalAllRentals) {
            return [
                'name' => $e->name,
                'total' => $e->rentals_count,
                'total_all' => $totalAllRentals 
            ];
        });
    }
}