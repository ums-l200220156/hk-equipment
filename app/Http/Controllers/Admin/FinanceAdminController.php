<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\Rental;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceAdminController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan Periode Filter (Default: Bulan Ini)
        $period = $request->get('period', 'monthly');
        $now = Carbon::now('Asia/Jakarta');
        $startDate = $now->copy()->startOfMonth();
        $endDate = $now->copy()->endOfMonth();

        if ($period == 'weekly') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
        } elseif ($period == 'yearly') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfYear();
        }

        // 2. Hitung Pemasukan Otomatis (Hanya status 'completed')
        // Dari Transaksi Utama
        $rentalIncome = Rental::where('status', 'completed')
            ->whereBetween('rent_date', [$startDate, $endDate])
            ->sum('total_price');

        // Dari Overtime (Lembur)
        $overtimeIncome = Overtime::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        // 3. Ambil Data Pengeluaran Manual dari Tabel Finances
        $records = Finance::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalExpense = $records->sum('amount');

        // 4. Hitung Saldo Bersih
        $totalIncome = $rentalIncome + $overtimeIncome;
        $netProfit = $totalIncome - $totalExpense;

        return view('admin.finance.index', compact(
            'records', 
            'rentalIncome', 
            'overtimeIncome', 
            'totalExpense', 
            'totalIncome',
            'netProfit', 
            'period'
        ));
    }

    public function store(Request $request)
    {
        // Simpan Pengeluaran Baru yang Diinput Owner
        $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        Finance::create([
            'type' => 'expense',
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return back()->with('swal_success', 'Catatan pengeluaran berhasil disimpan.');
    }

    public function destroy($id)
    {
        $finance = Finance::findOrFail($id);
        $finance->delete();

        return back()->with('swal_success', 'Catatan berhasil dihapus.');
    }
}