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
        //abort_if($rental->user_id !== Auth::id(), 403);
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

    // PERBAIKAN: Gunakan store('payment-proofs', 'public') agar tersimpan di storage/app/public/payment-proofs
    $path = $request->file('payment_proof')->store('payment-proofs', 'public');

    $rental->update([
        'payment_proof' => $path, 
        'status' => 'paid',
    ]);

    return redirect()->route('customer.rentals.show', $rental->id)
        ->with('success', 'Bukti transfer berhasil dikirim.');
}


  public function cancel(Rental $rental)
{
    abort_if($rental->user_id !== Auth::id(), 403);

    // Jangan izinkan batal jika sudah bayar atau berstatus selain waiting_payment
    if ($rental->status !== 'waiting_payment') {
        return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
    }

    \DB::transaction(function () use ($rental) {
        // 1. Update status sewa jadi cancelled
        $rental->update(['status' => 'cancelled']);

        // 2. Update status alat jadi available (Tersedia kembali)
        if ($rental->equipment) {
            $rental->equipment->update(['status' => 'available']);
        }
    });

    return redirect()->route('customer.rentals')
        ->with('success', 'Pesanan berhasil dibatalkan dan alat telah tersedia kembali.');
}




    // FUNGSI OVERTIME

    public function showOvertime($id)
    {
        $overtime = \App\Models\Overtime::with('rental.equipment')->findOrFail($id);
        abort_if($overtime->rental->user_id !== Auth::id(), 403);
        
        return view('customer.payment.overtime_show', compact('overtime'));
    }

    public function processOvertime(Request $request, $id)
    {
        $overtime = \App\Models\Overtime::findOrFail($id);
        $request->validate(['payment_method' => 'required|in:transfer,cash']);

        $overtime->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid'
        ]);

        if ($request->payment_method === 'transfer') {
            return redirect()->route('payment.overtime.transfer', $overtime->id);
        }

        return redirect()->route('customer.rentals.show', $overtime->rental_id)
            ->with('success', 'Metode Cash dipilih. Silakan bayar ke petugas di lapangan.');
    }

    public function transferOvertime($id)
    {
        $overtime = \App\Models\Overtime::findOrFail($id);
        return view('customer.payment.overtime_transfer', compact('overtime'));
    }

    public function uploadProofOvertime(Request $request, $id)
    {
        $overtime = \App\Models\Overtime::findOrFail($id);
        $request->validate(['proof' => 'required|image|max:2048']);

        $path = $request->file('proof')->store('overtime-proofs', 'public');

        $overtime->update([
            'proof' => $path,
            'payment_status' => 'paid', // Menunggu verifikasi admin tapi sudah ada bukti
        ]);

        return redirect()->route('customer.rentals.show', $overtime->rental_id)
            ->with('success', 'Bukti bayar overtime berhasil dikirim.');
    }
}
