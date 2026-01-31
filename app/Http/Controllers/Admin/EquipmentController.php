<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Menggunakan File Facade untuk operasi file


class EquipmentController extends Controller
{
    /**
     * Menampilkan daftar semua alat berat.
     */
    public function index(Request $request)
{
    $query = Equipment::query();

    // 🔍 Search nama alat
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 🏷️ Filter kategori
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // ⚙️ Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Data alat
    $equipment = $query->orderBy('id', 'DESC')->get();

    // Untuk dropdown filter
    $categories = Equipment::select('category')->distinct()->pluck('category');

    return view('admin.equipment.index', compact('equipment', 'categories'));
}


    /**
     * Menampilkan formulir penambahan alat berat baru.
     */
    public function create()
    {
        return view('admin.equipment.create');
    }

    /**
     * Menyimpan data alat berat baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name'          => 'required|max:255',
            'category'      => 'required',
            'price_per_hour'=> 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // image diizinkan kosong (nullable)
            // Tambahkan validasi untuk field lain jika diperlukan
        ]);

        // 2. Upload Gambar
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('uploads/equipment'), $imageName);
        }

        // 3. Simpan Data
        Equipment::create([
            'name'          => $request->name,
            'category'      => $request->category,
            'description'   => $request->description,
            'year'          => $request->year,
            'brand'         => $request->brand,
            'price_per_hour'=> $request->price_per_hour,
            'status'        => 'available', // Default status
            'image'         => $imageName,
        ]);

        // 4. Redirect dengan Notifikasi Sukses (SweetAlert2 Key)
        return redirect()->route('admin.equipment.index')
                         ->with('success', 'Alat berat "'.$request->name.'" berhasil ditambahkan ke armada.');
    }

    /**
     * Menampilkan formulir edit alat berat.
     */
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment.edit', compact('equipment'));
    }

    /**
     * Memperbarui data alat berat di database.
     */
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        // 1. Validasi Data
        $request->validate([
            'name'          => 'required|max:255',
            'category'      => 'required',
            'price_per_hour'=> 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // image boleh diupdate atau diabaikan
        ]);

        $imageName = $equipment->image;

        // 2. Penanganan Upload Gambar Baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($imageName && File::exists(public_path('uploads/equipment/'.$imageName))) {
                File::delete(public_path('uploads/equipment/'.$imageName));
            }

            // Simpan gambar baru
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('uploads/equipment'), $imageName);
        }
        
        // 3. Update Data
        $equipment->update([
            'name'          => $request->name,
            'category'      => $request->category,
            'description'   => $request->description,
            'year'          => $request->year,
            'brand'         => $request->brand,
            'price_per_hour'=> $request->price_per_hour,
            'status'        => $request->status ?? $equipment->status, // Pertahankan status jika tidak di form
            'image'         => $imageName,
        ]);

        // 4. Redirect dengan Notifikasi Sukses
        return redirect()->route('admin.equipment.index')
                         ->with('success', 'Data alat berat "'.$equipment->name.'" berhasil diperbarui.');
    }

    /**
     * Menghapus data alat berat dari database.
     */
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $alat_name = $equipment->name;

        // 1. Hapus File Gambar
        if ($equipment->image && File::exists(public_path('uploads/equipment/'.$equipment->image))) {
            File::delete(public_path('uploads/equipment/'.$equipment->image));
        }

        // 2. Hapus Record
        $equipment->delete();

        // 3. Redirect dengan Notifikasi Sukses
        return redirect()->route('admin.equipment.index')
                         ->with('success', 'Alat berat "'.$alat_name.'" berhasil dihapus permanen dari sistem.');
    }


    public function updateStatus(Request $request, $id)
        {
            $request->validate([
                'status' => 'required|in:available,rented,maintenance',
                'maintenance_end_at' => 'nullable|date|after_or_equal:today',
            ]);

            $equipment = Equipment::findOrFail($id);

            $equipment->update([
                 'status' => $request->status,
                'maintenance_end_at' =>
                    $request->status === 'maintenance'
                        ? $request->maintenance_end_at
                        : null,
            ]);

            return back()->with('success', 'Status alat berhasil diperbarui.');
        }

}