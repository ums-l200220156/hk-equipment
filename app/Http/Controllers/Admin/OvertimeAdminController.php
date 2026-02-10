<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\Request;

class OvertimeAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Overtime::with('rental.user', 'rental.equipment')
            ->whereIn('status', ['pending', 'approved', 'completed']);

        // LOGIKA FILTER PERIODE (UNTUK MONITORING SKRIPSI)
        if ($request->has('period') && $request->period !== 'all') {
            if ($request->period == 'weekly') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($request->period == 'monthly') {
                $query->where('created_at', '>=', now()->subDays(30));
            } elseif ($request->period == 'yearly') {
                $query->where('created_at', '>=', now()->subDays(365));
            }
        }

        $overtimes = $query->latest()->get();

        return view('admin.overtime.index', compact('overtimes'));
    }

    // FUNGSI MENYETUJUI
    public function approve(Request $request, $id)
    {
        $request->validate(['price_per_hour' => 'required|numeric|min:1000']);

        $ot = Overtime::findOrFail($id);
        $ot->update([
            'status' => 'approved',
            'price_per_hour' => $request->price_per_hour,
            'started_at' => now(), // Waktu lembur resmi dimulai
        ]);

        return back()->with('success', 'Overtime telah disetujui dan dimulai.');
    }

    // FUNGSI MENOLAK
    public function reject($id)
    {
        $ot = Overtime::findOrFail($id);
        $ot->update(['status' => 'rejected']);

        return back()->with('info', 'Pengajuan overtime telah ditolak.');
    }

    public function stop($id)
    {
        $ot = Overtime::findOrFail($id);

        // ✅ VALIDASI STATUS — HARUS APPROVED
        if ($ot->status !== 'approved') {
            return back()->with('error', 'Overtime belum aktif.');
        }

        // Pastikan waktu mulai ada
        if (!$ot->started_at) {
            return back()->with('error', 'Waktu mulai tidak tercatat.');
        }

        $ot->ended_at = now();

        $start = \Carbon\Carbon::parse($ot->started_at);
        $end = \Carbon\Carbon::parse($ot->ended_at);

        $totalSeconds = $start->diffInSeconds($end);

        $extraHours = $totalSeconds / 3600;
        $totalPrice = $extraHours * $ot->price_per_hour;

        $ot->update([
            'price' => max(0, $totalPrice),
            'status' => 'completed',
            'ended_at' => $ot->ended_at,
            'extra_hours' => $extraHours
        ]);

        return back()->with('success', 'Overtime berhasil dihentikan.');
    }

    public function destroy($id)
    {
        $ot = Overtime::findOrFail($id);

        // Proteksi: Jangan hapus jika status masih 'approved' (berjalan)
        if ($ot->status === 'approved') {
            return back()->with('error', 'Gagal menghapus! Hentikan lembur yang sedang berjalan terlebih dahulu.');
        }

        $ot->delete();

        return back()->with('success', 'Data overtime berhasil dihapus secara permanen.');
    }
}