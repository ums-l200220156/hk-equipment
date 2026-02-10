<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimoniAdminController extends Controller
{
    /**
     * 1. MENAMPILKAN DAFTAR TESTIMONI (ADMIN)
     */
    public function index()
    {
        // Mengambil semua testimoni beserta data usernya, diurutkan dari yang terbaru
        $testimonis = Testimoni::with('user')->latest()->get();
        
        return view('admin.testimoni.index', compact('testimonis'));
    }

    /**
     * 2. MENGHAPUS TESTIMONI (ADMIN)
     */
    public function destroy($id)
    {
        $testimoni = Testimoni::findOrFail($id);

        // Hapus file media (foto/video) dari storage jika ada
        if ($testimoni->photo) {
            Storage::disk('public')->delete($testimoni->photo);
        }
        if ($testimoni->video) {
            Storage::disk('public')->delete($testimoni->video);
        }

        // Hapus data dari database
        $testimoni->delete();

        // Kembali dengan notifikasi sukses (untuk SweetAlert)
        return redirect()->back()->with('swal_success', 'Testimoni pelanggan telah berhasil dihapus.');
    }
}