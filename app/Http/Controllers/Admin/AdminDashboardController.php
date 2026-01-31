<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Rental;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
{
    $totalEquipment = Equipment::count();
    $available = Equipment::where('status', 'available')->count();
    $rented = Equipment::where('status', 'rented')->count();

    $totalRentals = Rental::count();
    $pendingRentals = Rental::where('status', 'pending')->count();
    $completedRentals = Rental::where('status', 'completed')->count();

    $recentRentals = Rental::with('user', 'equipment')
        ->orderBy('id', 'DESC')
        ->take(5)
        ->get();

    // ⚠️ ALERT
    $overdueRentals = Rental::where('status', 'on_progress')
        ->whereDate('rent_date', '<', now()->subDays(1))
        ->count();

    // 📊 Grafik 7 hari
    $chart = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->toDateString();
        $chart[] = [
            'date' => $date,
            'total' => Rental::whereDate('created_at', $date)->count()
        ];
    }

    return view('admin.dashboard.index', compact(
        'totalEquipment',
        'available',
        'rented',
        'totalRentals',
        'pendingRentals',
        'completedRentals',
        'recentRentals',
        'overdueRentals',
        'chart'
    ));
}

}
