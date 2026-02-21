<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Models\Overtime;

class RentalAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with('user', 'equipment')->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })->orWhere('id', 'like', "%$search%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $rentals = $query->get();
        return view('admin.rentals.index', compact('rentals'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:waiting_payment,paid,approved,on_progress,completed,cancelled'
        ]);

        $rental = Rental::with('equipment')->findOrFail($id);

        if ($request->status === 'cancelled') {
            $rental->update(['status' => 'cancelled']);
            $rental->equipment->update(['status' => 'available']);
            return back()->with('swal_success', 'Penyewaan berhasil dibatalkan');
        }

        $rental->update(['status' => $request->status]);

        if (in_array($request->status, ['approved', 'on_progress'])) {
            $rental->equipment->update(['status' => 'rented']);
        }

        if ($request->status === 'completed') {
            $rental->equipment->update(['status' => 'available']);
        }

        return back()->with('swal_success', 'Status transaksi berhasil diperbarui');
    }

    public function create()
    {
        $users = User::where('role', 'customer')->get();
        $equipments = Equipment::where('status', 'available')->get();
        return view('admin.rentals.create', compact('users', 'equipments'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'equipment_id' => 'required|exists:equipment,id',
            'rent_date' => 'required|date',
            'start_time' => 'required',
            'duration_hours' => 'required|integer|min:1',
            'location' => 'required|string',
            'notes' => 'nullable|string|min:5|max:200',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        Rental::create([
            'user_id' => $request->user_id,
            'equipment_id' => $request->equipment_id,
            'rent_date' => $request->rent_date,
            'start_time' => $request->start_time,
            'duration_hours' => $request->duration_hours,
            'location' => $request->location,
            'notes' => $request->notes, 
            'total_price' => $equipment->price_per_hour * $request->duration_hours,
            'status' => 'approved', 
        ]);

        $equipment->update(['status' => 'rented']);
        return redirect()->route('admin.rentals.index')->with('swal_success', 'Penyewaan manual berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rental = Rental::findOrFail($id);
        $equipments = Equipment::all();
        return view('admin.rentals.edit', compact('rental', 'equipments'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'rent_date' => 'required|date',
            'start_time' => 'required',
            'duration_hours' => 'required|integer|min:1',
            'location' => 'required|string',
            'notes' => 'nullable|string|min:5|max:200'
        ]);

        $rental = Rental::findOrFail($id);
        $equipment = Equipment::findOrFail($request->equipment_id);
        $total = $equipment->price_per_hour * $request->duration_hours;

        $rental->update([
            'equipment_id' => $request->equipment_id,
            'rent_date' => $request->rent_date,
            'start_time' => $request->start_time,
            'duration_hours' => $request->duration_hours,
            'location' => $request->location,
            'notes' => $request->notes,
            'total_price' => $total
        ]);

        return redirect()->route('admin.rentals.index')->with('swal_success', 'Perubahan transaksi berhasil disimpan.');
    }

    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);
        if(in_array($rental->status, ['waiting_payment', 'approved', 'on_progress'])) {
            $rental->equipment->update(['status' => 'available']);
        }
        $rental->delete();
        return back()->with('swal_success', 'Data transaksi berhasil dihapus.');
    }

    public function updateOvertime(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $overtime = Overtime::with('rental.equipment')->findOrFail($id);
        $overtime->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            $overtime->rental->update(['status' => 'on_progress']);
        }
        return back()->with('swal_success', 'Status overtime berhasil dikonfirmasi.');
    }
}