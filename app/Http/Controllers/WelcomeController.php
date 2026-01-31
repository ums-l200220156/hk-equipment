<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;

class WelcomeController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::with('user')
            ->latest()
            ->get();

        return view('welcome', compact('testimonis'));
    }
}
