<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /* ===============================
       AMBIL RENTAL MILIK USER LOGIN
    =============================== */
    private function getRental($id)
    {
        return Rental::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    /* ===============================
       HALAMAN PEMBAYARAN
    =============================== */
    public function show($id)
    {
        $rental = $this->getRental($id);

        return view('customer.payment.show', compact('rental'));
    }

    /* ===============================
       PILIH METODE PEMBAYARAN
    =============================== */
    public function process(Request $request, $id)
    {
        $rental = $this->getRental($id);

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

    /* ===============================
       HALAMAN TRANSFER
    =============================== */
    public function transfer($id)
    {
        $rental = $this->getRental($id);

        return view('customer.payment.transfer', compact('rental'));
    }

    /* ===============================
       UPLOAD BUKTI TRANSFER
    =============================== */
    public function uploadProof(Request $request, $id)
    {
        $rental = $this->getRental($id);

        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $rental->update([
            'payment_proof' => $path,
            'status' => 'paid',
        ]);

        return redirect()->route('customer.rentals.show', $rental->id)
            ->with('success', 'Bukti transfer berhasil dikirim.');
    }

    /* ===============================
       BATALKAN PESANAN
    =============================== */
    public function cancel($id)
    {
        $rental = $this->getRental($id);

        if ($rental->status !== 'waiting_payment') {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($rental) {

            $rental->update([
                'status' => 'cancelled'
            ]);

            if ($rental->equipment) {
                $rental->equipment->update([
                    'status' => 'available'
                ]);
            }
        });

        return redirect()->route('customer.rentals')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }


    /* ===============================
       OVERTIME PAYMENT
    =============================== */

    public function showOvertime($id)
    {
        $overtime = \App\Models\Overtime::with('rental.equipment')->findOrFail($id);

        if ($overtime->rental->user_id !== Auth::id()) {
            abort(404);
        }

        return view('customer.payment.overtime_show', compact('overtime'));
    }

    public function processOvertime(Request $request, $id)
    {
        $overtime = \App\Models\Overtime::findOrFail($id);

        $request->validate([
            'payment_method' => 'required|in:transfer,cash'
        ]);

        $overtime->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid'
        ]);

        if ($request->payment_method === 'transfer') {
            return redirect()->route('payment.overtime.transfer', $overtime->id);
        }

        return redirect()->route('customer.rentals.show', $overtime->rental_id)
            ->with('success', 'Metode Cash dipilih. Silakan bayar ke petugas.');
    }

    public function transferOvertime($id)
    {
        $overtime = \App\Models\Overtime::findOrFail($id);

        return view('customer.payment.overtime_transfer', compact('overtime'));
    }

    public function uploadProofOvertime(Request $request, $id)
    {
        $overtime = \App\Models\Overtime::findOrFail($id);

        $request->validate([
            'proof' => 'required|image|max:2048'
        ]);

        $path = $request->file('proof')->store('overtime-proofs', 'public');

        $overtime->update([
            'proof' => $path,
            'payment_status' => 'paid'
        ]);

        return redirect()->route('customer.rentals.show', $overtime->rental_id)
            ->with('success', 'Bukti bayar overtime berhasil dikirim.');
    }
}