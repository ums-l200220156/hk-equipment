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

        // ✅ VALIDASI LENGKAP & AMAN
        $request->validate([
            'rent_date' => 'required|date|after_or_equal:today',
            'start_time' => ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'duration_hours' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'rent_date' => $request->rent_date,
            'start_time' => $request->start_time, // contoh: 21:00
            'duration_hours' => $request->duration_hours,
            'location' => $request->location,
            'notes' => $request->notes,
            'total_price' => $equipment->price_per_hour * $request->duration_hours,
            'status' => 'waiting_payment',
        ]);

        // Alat langsung dikunci
        $equipment->update(['status' => 'rented']);

        return redirect()->route('payment.show', $rental->id);
    }

    /**
     * Customer membatalkan penyewaan
     */
    public function cancel($id)
{
    $rental = Rental::where('id', $id)
        ->where('user_id', Auth::id())
        ->with('equipment')
        ->firstOrFail();

    // PERUBAHAN LOGIKA: Hanya bisa batal jika Menunggu Pembayaran
    if ($rental->status !== 'waiting_payment') {
        return back()->with(
            'error',
            'Penyewaan tidak dapat dibatalkan karena pembayaran sudah dilakukan atau sedang diproses.'
        );
    }

    $rental->update(['status' => 'cancelled']);
    $rental->equipment->update(['status' => 'available']);

    return redirect()
        ->route('customer.rentals')
        ->with('success', 'Penyewaan berhasil dibatalkan.');
}
}
