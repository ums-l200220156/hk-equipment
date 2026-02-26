<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * =====================================================
     * INDEX – KATALOG ALAT BERAT
     * =====================================================
     */
    public function index(Request $request)
    {
        $query = Equipment::query();

        /**
         * SEARCH (berdasarkan nama alat)
         */
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . trim($request->search) . '%');
        }

        /**
         * FILTER KATEGORI 
         * Logic: Jika 'lock' aktif (dari homepage), gunakan pencarian persis (=).
         * Jika filter manual di katalog, gunakan pencarian kemiripan (LIKE).
         */
        $lockedCategory = null;

        if ($request->filled('category')) {
            $category = strtolower(trim($request->category));

            if ($request->boolean('lock')) {
                // Pencarian PERSIS agar Excavator Standard tidak menampilkan Breaker
                $query->whereRaw('LOWER(category) = ?', [$category]);
                $lockedCategory = $category;
            } else {
                // Pencarian kemiripan untuk filter manual di halaman katalog
                $query->whereRaw('LOWER(category) LIKE ?', ["%{$category}%"]);
            }
        }

        /**
         * FILTER STATUS (opsional)
         */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /**
         * URUTAN PROFESIONAL
         * available → rented → maintenance
         */
        $equipment = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'available' THEN 0
                    WHEN status = 'rented' THEN 1
                    WHEN status = 'maintenance' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        /**
         * DROPDOWN KATEGORI (UNTUK FILTER)
         */
        $categories = Equipment::select('category')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        /**
         * RETURN VIEW
         */
        return view('customer.catalog.index', [
            'equipment'       => $equipment,
            'categories'      => $categories,
            'lockedCategory'  => $lockedCategory, 
        ]);
    }

    /**
     * SHOW – DETAIL ALAT
     */
    public function show($id)
    {
        $item = Equipment::findOrFail($id);
        return view('customer.catalog.show', compact('item'));
    }

    /**
     * STATUS ENDPOINT (JSON)
     * Dipakai auto-refresh tanpa reload
     */
    public function status($id)
    {
        $item = Equipment::select(
                'id',
                'status',
                'maintenance_end_at'
            )
            ->findOrFail($id);

        return response()->json([
            'status' => $item->status,
            'maintenance_end_at' => $item->maintenance_end_at
                ? $item->maintenance_end_at->translatedFormat('d F Y')
                : null,
        ]);
    }
}