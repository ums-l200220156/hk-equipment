<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    /**
     * Form sewa alat
     */
    public function create($id)
    {
        $equipment = Equipment::findOrFail($id);

        // Proteksi awal: Jika status sudah tidak tersedia, jangan kasih masuk ke form
        if ($equipment->status !== 'available') {
            return redirect()->route('customer.catalog')
                ->with('error', 'Maaf, alat ini sedang tidak tersedia untuk disewa.');
        }

        return view('customer.rent.create', compact('equipment'));
    }

    /**
     * Simpan permintaan sewa dengan proteksi Race Condition
     */
    public function store(Request $request, $id)
    {
        // 1. Validasi input dasar
        $request->validate([
            'rent_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i', 
            'duration_hours' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            /**
             * 2. Database Transaction & Locking
             * Menggunakan DB::transaction untuk memastikan atomicity (semua sukses atau semua batal).
             */
            return DB::transaction(function () use ($request, $id) {
                
                /**
                 * 3. lockForUpdate() 
                 * Ini kunci utamanya. Database akan mengunci baris alat ini.
                 * Customer lain yang mencoba mengakses alat yang sama akan "antre" (wait)
                 * sampai transaksi ini selesai (Commit atau Rollback).
                 */
                $equipment = Equipment::lockForUpdate()->findOrFail($id);

                // 4. Verifikasi Ulang (Double Check)
                // Cek apakah selama customer mengisi form, alat ini sudah disambar orang lain?
                if ($equipment->status !== 'available') {
                    return redirect()->route('customer.catalog')
                        ->with('error', 'Waduh, keduluan! Alat ini baru saja disewa oleh customer lain. Silakan pilih armada kami yang lain.');
                }

                // 5. Buat data penyewaan
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

                // 6. Update status alat segera agar kunci (lock) untuk user lain melihat status 'rented'
                $equipment->update(['status' => 'rented']);

                return redirect()->route('payment.show', $rental->id)
                    ->with('success', 'Pesanan berhasil dibuat! Segera selesaikan pembayaran Anda.');
            });

        } catch (\Exception $e) {
            // Log error jika terjadi kegagalan teknis pada database
            Log::error("Rental Store Error: " . $e->getMessage());

            return redirect()->route('customer.catalog')
                ->with('error', 'Terjadi kendala pada sistem. Silakan coba beberapa saat lagi.');
        }
    }

    /**
     * Customer membatalkan penyewaan
     */
    public function cancel($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $rental = Rental::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->with('equipment')
                    ->lockForUpdate() // Kunci juga saat pembatalan
                    ->firstOrFail();

                // Hanya bisa batal jika status masih waiting_payment
                if ($rental->status !== 'waiting_payment') {
                    return back()->with(
                        'error',
                        'Penyewaan tidak dapat dibatalkan karena sudah diproses atau selesai.'
                    );
                }

                // Kembalikan status
                $rental->update(['status' => 'cancelled']);
                
                if ($rental->equipment) {
                    $rental->equipment->update(['status' => 'available']);
                }

                return redirect()->route('customer.rentals')
                    ->with('success', 'Penyewaan berhasil dibatalkan dan alat telah tersedia kembali.');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan penyewaan.');
        }
    }
}