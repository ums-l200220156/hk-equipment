<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Equipment, Rental, Overtime, Finance, Testimoni};
use Barryvdh\DomPDF\Facade\Pdf;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. UNIT & FLEET ANALYTICS
        $totalEquipment = Equipment::count();
        $available = Equipment::where('status', 'available')->count();
        $rented = Equipment::where('status', 'rented')->count();
        $underMaintenance = Equipment::where('status', 'maintenance')->count();

        // 2. FINANCIAL PERFORMANCE
        $now = now();
        $m = $now->month;
        $y = $now->year;

        $lastMonthDate = now()->subMonth();
        $lm = $lastMonthDate->month;
        $ly = $lastMonthDate->year;

        $rentalInc = Rental::where('status', 'completed')
            ->whereMonth('rent_date', $m)
            ->whereYear('rent_date', $y)
            ->sum('total_price');

        $overtimeInc = Overtime::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', $m)
            ->whereYear('created_at', $y)
            ->sum('price');

        $expensesSum = Finance::where('type', 'expense')
            ->whereMonth('transaction_date', $m)
            ->whereYear('transaction_date', $y)
            ->sum('amount');

        $netProfit = ($rentalInc + $overtimeInc) - $expensesSum;

        // 3. GROWTH
        $thisMonthCount = Rental::whereMonth('created_at', $m)->whereYear('created_at', $y)->count();
        $lastMonthCount = Rental::whereMonth('created_at', $lm)->whereYear('created_at', $ly)->count();

        $growth = $lastMonthCount > 0
            ? (($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100
            : 100;

        // 4. METRICS
        $utilizationRate = $totalEquipment > 0 ? round(($rented / $totalEquipment) * 100) : 0;
        $customerSatisfaction = round(Testimoni::avg('rating') ?? 5.0, 1);

        $recentRentals = Rental::with(['user', 'equipment'])->latest()->take(6)->get();

        // 5. ALERTS
        $alerts = [];

        $newRentalsCount = Rental::whereIn('status', ['waiting_payment', 'paid'])
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($newRentalsCount > 0) {
            $alerts[] = ["type" => "success", "msg" => "Terdapat $newRentalsCount penyewaan baru masuk!"];
        }

        $pendingOvertime = Overtime::where('status', 'pending')->count();
        if ($pendingOvertime > 0) {
            $alerts[] = ["type" => "info", "msg" => "Ada $pendingOvertime permintaan lembur menunggu persetujuan."];
        }

        $overdueRentals = Rental::where('status', 'on_progress')
            ->whereDate('rent_date', '<', now())
            ->count();

        if ($overdueRentals > 0) {
            $alerts[] = ["type" => "error", "msg" => "Critical: $overdueRentals Unit Telat Dikembalikan!"];
        }

        if ($underMaintenance > 3) {
            $alerts[] = ["type" => "warning", "msg" => "Fleet maintenance bottleneck ($underMaintenance unit)."];
        }

        // 6. CHART
        $months = [];
        $incomeData = [];

        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $months[] = $d->format('M Y');

            $incomeData[] = Rental::where('status', 'completed')
                ->whereMonth('rent_date', $d->month)
                ->whereYear('rent_date', $d->year)
                ->sum('total_price');
        }

        return view('admin.dashboard.index', compact(
            'totalEquipment', 'available', 'rented', 'underMaintenance',
            'netProfit', 'utilizationRate', 'customerSatisfaction',
            'recentRentals', 'months', 'incomeData', 'alerts',
            'growth', 'thisMonthCount'
        ));
    }

    /**
     * CORE DATA REPORT (FULL DETAIL)
     */
    private function getReportData()
    {
        $now = now();
        $m = $now->month;
        $y = $now->year;

        $lastMonthDate = now()->subMonth();

        // RENTAL
        $rentalDetails = Rental::with(['user', 'equipment'])
            ->where('status', 'completed')
            ->whereMonth('rent_date', $m)
            ->whereYear('rent_date', $y)
            ->get();

        // OVERTIME
        $overtimeDetails = Overtime::with(['rental.user'])
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', $m)
            ->whereYear('created_at', $y)
            ->get();

        // EXPENSE
        $expenseDetails = Finance::where('type', 'expense')
            ->whereMonth('transaction_date', $m)
            ->whereYear('transaction_date', $y)
            ->get();

        // CALCULATION
        $totalIncome = $rentalDetails->sum('total_price') + $overtimeDetails->sum('price');
        $totalExpense = $expenseDetails->sum('amount');
        $profit = $totalIncome - $totalExpense;

        // GROWTH
        $thisMonthCount = Rental::whereMonth('created_at', $m)->whereYear('created_at', $y)->count();
        $lastMonthCount = Rental::whereMonth('created_at', $lastMonthDate->month)
            ->whereYear('created_at', $lastMonthDate->year)
            ->count();

        $growth = $lastMonthCount > 0
            ? (($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100
            : 100;

        return [
            'date' => $now->translatedFormat('d F Y'),
            'month_name' => $now->translatedFormat('F Y'),
            'rentalDetails' => $rentalDetails,
            'overtimeDetails' => $overtimeDetails,
            'expenseDetails' => $expenseDetails,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'profit' => $profit,
            'completed' => $rentalDetails->count(),
            'thisMonth' => $thisMonthCount,
            'lastMonth' => $lastMonthCount,
            'growth' => round($growth, 1),
            'statusGrowth' => $growth >= 0 ? 'Peningkatan' : 'Penurunan',
            'now' => $now
        ];
    }

    /**
     * EXPORT PDF ONLY
     */
    public function exportPDF()
    {
        $data = $this->getReportData();

        $pdf = Pdf::loadView('admin.report.report-pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download(
            'Laporan_HK_Equipment_' . $data['now']->format('M_Y') . '.pdf'
        );
    }

    /**
     * REALTIME ALERT API
     */
    public function getAlerts()
    {
        $alerts = [];

        $newRentalsCount = Rental::whereIn('status', ['waiting_payment', 'paid'])
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        if ($newRentalsCount > 0) {
            $alerts[] = ["type" => "success", "msg" => "Ada $newRentalsCount rental baru!"];
        }

        $pendingOvertime = Overtime::where('status', 'pending')->count();
        if ($pendingOvertime > 0) {
            $alerts[] = ["type" => "info", "msg" => "$pendingOvertime overtime pending"];
        }

        $overdue = Rental::where('status', 'on_progress')
            ->whereDate('rent_date', '<', now())
            ->count();

        if ($overdue > 0) {
            $alerts[] = ["type" => "error", "msg" => "$overdue unit overdue!"];
        }

        return response()->json($alerts);
    }
}