<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Models\Overtime;

class RentalAdminController extends Controller
{
    /**
     * Halaman kelola transaksi
     */
    public function index(Request $request)
    {
        $query = Rental::with('user', 'equipment')->orderBy('id', 'DESC');

        // Filter status (opsional)
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $rentals = $query->get();

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Update status sewa
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:waiting_payment,paid,approved,on_progress,completed,cancelled'
    ]);

    $rental = Rental::with('equipment')->findOrFail($id);

    // Jika admin membatalkan
    if ($request->status === 'cancelled') {
        $rental->update(['status' => 'cancelled']);

        // Alat dikembalikan
        $rental->equipment->update([
            'status' => 'available'
        ]);

        return back()->with('success', 'Penyewaan berhasil dibatalkan');
    }

    // Update normal
    $rental->update([
        'status' => $request->status
    ]);

    // Sinkron status alat
    if (in_array($request->status, ['approved', 'on_progress'])) {
        $rental->equipment->update(['status' => 'rented']);
    }

    if ($request->status === 'completed') {
        $rental->equipment->update(['status' => 'available']);
    }

    return back()->with('success', 'Status berhasil diperbarui');
}




    public function updateOvertime(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected'
    ]);

    $overtime = Overtime::with('rental.equipment')->findOrFail($id);

    $overtime->update([
        'status' => $request->status
    ]);

    // Jika disetujui, rental tetap berjalan
    if ($request->status === 'approved') {
        $overtime->rental->update([
            'status' => 'on_progress'
        ]);
    }

    return back()->with('swal_success', true);
}


}
