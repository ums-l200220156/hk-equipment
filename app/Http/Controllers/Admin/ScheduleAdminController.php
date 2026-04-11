<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleAdminController extends Controller
{
    //  HALAMAN UTAMA
    public function index()
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('admin.schedule.index', compact('equipment'));
    }

    //  EVENT CALENDAR
    public function events(Request $request)
    {
        $query = Rental::with(['equipment','user'])
            ->whereIn('status', ['pending','approved','on_progress']);

        if ($request->equipment_id) {
            $query->where('equipment_id', $request->equipment_id);
        }

        $events = $query->get()->map(function ($r) {

            // Pastikan durasi selalu integer
            $duration = (int) ($r->duration_hours ?? 1);

            // Gunakan timezone Jakarta
            $start = Carbon::parse($r->rent_date.' '.$r->start_time, 'Asia/Jakarta');
            $end   = $start->copy()->addHours($duration);

            return [
                'id' => $r->id,
                'title' => $r->equipment->name ?? 'Equipment',
                'start' => $start->toIso8601String(),
                'end'   => $end->toIso8601String(),

                'backgroundColor' => match($r->status) {
                    'pending'     => '#facc15',
                    'approved'    => '#3b82f6',
                    'on_progress' => '#f97316',
                    default       => '#22c55e'
                },

                'extendedProps' => [
                    'customer' => $r->user->name ?? 'Customer',
                    'status'   => $r->status,
                ]
            ];
        });

        return response()->json($events);
    }

    //  DRAG & DROP + ⚠️ CEK BENTROK
    public function update(Request $request)
    {
        $rental = Rental::findOrFail($request->id);

        // Gunakan timezone Jakarta
        $start = Carbon::parse($request->start, 'Asia/Jakarta');
        $end   = Carbon::parse($request->end, 'Asia/Jakarta');

        $hours = max(1, (int) $start->diffInHours($end));

        // ⚠️ DETEKSI BENTROK
        $conflict = Rental::where('equipment_id', $rental->equipment_id)
            ->where('id', '!=', $rental->id)
            ->where(function ($q) use ($start, $end) {
                $q->whereRaw(
                    "TIMESTAMP(rent_date, start_time) < ? 
                     AND TIMESTAMP(rent_date, start_time) + INTERVAL duration_hours HOUR > ?",
                    [$end, $start]
                );
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'Bentrok jadwal dengan sewa lain'
            ], 422);
        }

        $rental->update([
            'rent_date' => $start->toDateString(),
            'start_time' => $start->format('H:i'),
            'duration_hours' => (int) $hours
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    //  HEATMAP PEMAKAIAN 
    public function heatmap(Request $request)
    {
        $period = $request->query('period', 'all');
        $now = now();

        // Filter status + periode
        $queryFilter = function ($query) use ($period, $now) {

            $query->whereIn('status', ['on_progress', 'completed']);

            if ($period == 'week') {

                $query->whereBetween('rent_date', [
                    $now->copy()->startOfWeek()->toDateString(),
                    $now->copy()->endOfWeek()->toDateString()
                ]);

            } elseif ($period == 'month') {

                $query->whereMonth('rent_date', $now->month)
                      ->whereYear('rent_date', $now->year);

            } elseif ($period == 'year') {

                $query->whereYear('rent_date', $now->year);

            }
        };

        // Total seluruh rental pada periode
        $totalAllRentals = Rental::where($queryFilter)->count();

        // Data heatmap per alat
        $data = Equipment::withCount(['rentals' => $queryFilter])
            ->get()
            ->map(function ($e) use ($totalAllRentals) {

                return [
                    'name' => $e->name,
                    'total' => $e->rentals_count,
                    'total_all' => $totalAllRentals
                ];
            });

        return response()->json($data);
    }
}