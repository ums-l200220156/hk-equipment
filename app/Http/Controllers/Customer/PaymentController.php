<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Rental $rental)
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.payment.show', compact('rental'));
    }

    public function process(Request $request, Rental $rental)
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:transfer,cash',
        ]);

        $rental->update([
            'payment_method' => $request->payment_method,
            'status' => 'paid',
        ]);

        return redirect()->route('customer.rentals')
            ->with('success', 'Pembayaran berhasil.');
    }
}
