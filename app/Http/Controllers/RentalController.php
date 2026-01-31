<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    /**
     * Form sewa alat
     */
    public function create($id)
    {
        $equipment = Equipment::findOrFail($id);

        if ($equipment->status !== 'available') {
            return redirect()->route('customer.catalog')
                ->with('error', 'Alat tidak tersedia.');
        }

        return view('customer.rent.create', compact('equipment'));
    }

    /**
     * Simpan permintaan sewa → redirect ke pembayaran
     */
    public function store(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'rent_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration_hours' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'rent_date' => $request->rent_date,
            'start_time' => $request->start_time,
            'duration_hours' => $request->duration_hours,
            'location' => $request->location,
            'notes' => $request->notes,
            'total_price' => $equipment->price_per_hour * $request->duration_hours,
            'status' => 'waiting_payment',
        ]);

        // Alat langsung dikunci (tidak bisa disewa user lain)
        $equipment->update(['status' => 'rented']);

        // Redirect ke halaman pembayaran
        return redirect()->route('payment.show', $rental->id);
    }

    /**
     * ❌ CUSTOMER MEMBATALKAN PENYEWAAN
     * HANYA BOLEH sebelum disetujui admin
     */
    public function cancel($id)
    {
        $rental = Rental::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('equipment')
            ->firstOrFail();

        // Validasi status
        if (!in_array($rental->status, ['waiting_payment', 'paid'])) {
            return back()->with(
                'error',
                'Penyewaan tidak dapat dibatalkan karena sudah diproses admin.'
            );
        }

        // Batalkan rental
        $rental->update([
            'status' => 'cancelled'
        ]);

        // Kembalikan alat
        $rental->equipment->update([
            'status' => 'available'
        ]);

        return redirect()
            ->route('customer.rentals')
            ->with('success', 'Penyewaan berhasil dibatalkan.');
    }
}
