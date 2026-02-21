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
    // 1. Data Unit & Operasional
    $totalEquipment = Equipment::count();
    $available = Equipment::where('status', 'available')->count();
    $rented = Equipment::where('status', 'rented')->count();
    $underMaintenance = Equipment::where('status', 'maintenance')->count();

    // 2. Data Transaksi & Anomali
    $pendingRentals = Rental::where('status', 'pending')->count();
    $overdueRentals = Rental::where('status', 'on_progress')->whereDate('rent_date', '<', now())->count();
    $recentRentals = Rental::with('user', 'equipment')->latest()->take(5)->get();

    // 3. Data Finansial (Bulan Ini)
    $rentalIncome = Rental::where('status', 'completed')->whereMonth('rent_date', now()->month)->sum('total_price');
    $overtimeIncome = \App\Models\Overtime::where('status', 'completed')->where('payment_status', 'paid')->whereMonth('created_at', now()->month)->sum('price');
    $totalExpense = \App\Models\Finance::where('type', 'expense')->whereMonth('transaction_date', now()->month)->sum('amount');
    $netProfit = ($rentalIncome + $overtimeIncome) - $totalExpense;

    // 4. Data Grafik Finansial (6 Bulan Terakhir)
    $months = [];
    $incomeData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $months[] = $month->format('M Y');
        
        $rInc = Rental::where('status', 'completed')->whereMonth('rent_date', $month->month)->whereYear('rent_date', $month->year)->sum('total_price');
        $oInc = \App\Models\Overtime::where('status', 'completed')->where('payment_status', 'paid')->whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->sum('price');
        
        $incomeData[] = $rInc + $oInc;
    }

    return view('admin.dashboard.index', compact(
        'totalEquipment', 'available', 'rented', 'underMaintenance',
        'pendingRentals', 'overdueRentals', 'recentRentals',
        'rentalIncome', 'overtimeIncome', 'totalExpense', 'netProfit',
        'months', 'incomeData'
    ));
}
}