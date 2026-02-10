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
        abort_if($rental->user_id !== Auth::id(), 403);
        return view('customer.payment.show', compact('rental'));
    }

    public function process(Request $request, Rental $rental)
    {
        abort_if($rental->user_id !== Auth::id(), 403);

        $request->validate([
            'payment_method' => 'required|in:transfer,cash',
        ]);

        $rental->update([
            'payment_method' => $request->payment_method,
            'status' => 'waiting_payment',
        ]);

        if ($request->payment_method === 'transfer') {
            return redirect()->route('payment.transfer', $rental->id);
        }

        return redirect()->route('customer.rentals.show', $rental->id)
            ->with('success', 'Pembayaran cash dipilih. Menunggu konfirmasi admin.');
    }

    public function transfer(Rental $rental)
    {
        abort_if($rental->user_id !== Auth::id(), 403);
        return view('customer.payment.transfer', compact('rental'));
    }

    public function uploadProof(Request $request, Rental $rental)
    {
        abort_if($rental->user_id !== Auth::id(), 403);

        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $rental->update([
            'payment_proof' => $path,
            'status' => 'paid',
        ]);

        return redirect()->route('customer.rentals.show', $rental->id)
            ->with('success', 'Bukti transfer berhasil dikirim. Menunggu konfirmasi admin.')
            ->with('just_paid', true);
    }

    public function cancel(Rental $rental)
    {
        abort_if($rental->user_id !== Auth::id(), 403);

        if ($rental->status === 'paid') {
            return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
        }

        $rental->update(['status' => 'cancelled']);

        return redirect()->route('customer.rentals')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
