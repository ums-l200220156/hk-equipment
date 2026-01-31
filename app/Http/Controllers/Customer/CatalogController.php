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
         * =================================================
         * SEARCH (nama alat)
         * =================================================
         */
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . trim($request->search) . '%');
        }

        /**
         * =================================================
         * FILTER KATEGORI
         * (dipakai oleh homepage "Cek Ketersediaan")
         * =================================================
         */
        $lockedCategory = null;

        if ($request->filled('category')) {
            $category = strtolower(trim($request->category));

            $lockedCategory = null;

            if ($request->filled('category')) {
                $category = strtolower(trim($request->category));

                $query->whereRaw(
                    'LOWER(category) LIKE ?',
                    ["%{$category}%"]
                );

                if ($request->boolean('lock')) {
                    $lockedCategory = $category;
                }
            }


                        // Jika datang dari homepage → kunci filter
                        if ($request->boolean('lock')) {
                            $lockedCategory = $category;
                        }
                    }

        /**
         * =================================================
         * FILTER STATUS (opsional, frontend JS)
         * =================================================
         */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /**
         * =================================================
         * URUTAN PROFESIONAL
         * available → rented → maintenance
         * =================================================
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
         * =================================================
         * DROPDOWN KATEGORI (UNTUK FILTER)
         * =================================================
         */
        $categories = Equipment::select('category')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        /**
         * =================================================
         * RETURN VIEW
         * =================================================
         */
        return view('customer.catalog.index', [
            'equipment'       => $equipment,
            'categories'      => $categories,
            'lockedCategory'  => $lockedCategory, // ⬅️ PENTING
        ]);
    }

    /**
     * =====================================================
     * SHOW – DETAIL ALAT
     * =====================================================
     */
    public function show($id)
    {
        $item = Equipment::findOrFail($id);

        return view('customer.catalog.show', compact('item'));
    }

    /**
     * =====================================================
     * STATUS ENDPOINT (JSON)
     * Dipakai auto-refresh tanpa reload
     * =====================================================
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
