<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerAdminController extends Controller
{
    /**
     * Daftar pelanggan
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.customer.index', compact('customers'));
    }

    /**
     * Form tambah pelanggan
     */
    public function create()
    {
        return view('admin.customer.create');
    }

    /**
     * Simpan pelanggan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'notes'    => 'nullable|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
            'notes'    => $request->notes,
            'role'     => 'customer',
        ]);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Form edit pelanggan
     */
    public function edit($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    /**
     * Update data pelanggan (FULL)
     */
    public function update(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|email|unique:users,email,' . $customer->id,
            'address' => 'required|string',
            'notes'   => 'nullable|string',
        ]);

        $customer->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'notes'   => $request->notes,
        ]);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus pelanggan
     */
    public function destroy($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->delete();

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
