<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;

class RentalHistoryController extends Controller
{
    /**
     * Tampilkan riwayat penyewaan customer
     * Urut dari transaksi TERBARU
     */
    public function index()
    {
        $rentals = Rental::where('user_id', Auth::id())
            ->with('equipment')
            ->latest() // ORDER BY created_at DESC
            ->get();

        return view('customer.rentals.index', compact('rentals'));
    }

    /**
     * Detail penyewaan (hanya milik user login)
     */
    public function show($id)
    {
        $rental = Rental::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('equipment')
            ->firstOrFail();

        return view('customer.rentals.show', compact('rental'));
    }
}
