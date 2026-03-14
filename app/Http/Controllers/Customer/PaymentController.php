<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Overtime;
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
       AMBIL OVERTIME MILIK USER LOGIN
    =============================== */
    private function getOvertime($id)
    {
        return Overtime::where('id', $id)
            ->whereHas('rental', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with('rental.equipment')
            ->firstOrFail();
    }


    /* ===============================
       HALAMAN PEMBAYARAN RENTAL
    =============================== */
    public function show($id)
    {
        $rental = $this->getRental($id);

        return view('customer.payment.show', compact('rental'));
    }


    /* ===============================
       PILIH METODE PEMBAYARAN RENTAL
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
       HALAMAN TRANSFER RENTAL
    =============================== */
    public function transfer($id)
    {
        $rental = $this->getRental($id);

        return view('customer.payment.transfer', compact('rental'));
    }


    /* ===============================
       UPLOAD BUKTI TRANSFER RENTAL
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
       BATALKAN PESANAN RENTAL
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



    /* ======================================================
       ================= OVERTIME PAYMENT ===================
       ====================================================== */


    /* ===============================
       HALAMAN PEMBAYARAN OVERTIME
    =============================== */
    public function showOvertime($id)
    {
        $overtime = $this->getOvertime($id);

        return view('customer.payment.overtime_show', compact('overtime'));
    }


    /* ===============================
       PILIH METODE PEMBAYARAN OVERTIME
    =============================== */
    public function processOvertime(Request $request, $id)
    {
        $overtime = $this->getOvertime($id);

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


    /* ===============================
       HALAMAN TRANSFER OVERTIME
    =============================== */
    public function transferOvertime($id)
    {
        $overtime = $this->getOvertime($id);

        return view('customer.payment.overtime_transfer', compact('overtime'));
    }


    /* ===============================
       UPLOAD BUKTI BAYAR OVERTIME
    =============================== */
    public function uploadProofOvertime(Request $request, $id)
    {
        $overtime = $this->getOvertime($id);

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