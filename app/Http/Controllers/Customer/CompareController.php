<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Halaman pilih alat
     */
    public function select()
    {
        $equipment = Equipment::orderBy('status', 'desc')
            ->orderBy('category')
            ->get();

        return view('customer.compare.select', compact('equipment'));
    }

    /**
     * Hasil perbandingan
     */
    public function result(Request $request)
{
    $ids = explode(',', $request->items);

    if(count($ids) < 2){
        return redirect()->back()->with('error','Pilih minimal 2 alat');
    }

    $items = Equipment::whereIn('id',$ids)->get();
    return view('customer.compare.result', compact('items'));
}

}
