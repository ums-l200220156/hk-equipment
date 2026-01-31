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

            $start = Carbon::parse($r->rent_date.' '.$r->start_time);
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

        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);
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
    public function heatmap()
    {
        return Equipment::withCount('rentals')->get()->map(function ($e) {
            return [
                'name' => $e->name,
                'total' => $e->rentals_count
            ];
        });
    }
}
