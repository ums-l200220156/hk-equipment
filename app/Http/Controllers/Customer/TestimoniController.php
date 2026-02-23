<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function store(Request $request)
{
    try {
        if (Testimoni::where('user_id', auth()->id())->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengirim satu testimoni.'
            ], 422);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
            'media'   => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ]);

        $photo = null;
        $video = null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');

            if (str_starts_with($file->getMimeType(), 'image/')) {
                $photo = $file->store('testimoni/photos', 'public');
            } else {
                $video = $file->store('testimoni/videos', 'public');
            }
        }

        Testimoni::create([
            'user_id' => auth()->id(),
            'rating'  => $validated['rating'],
            'content' => $validated['content'],
            'photo'   => $photo,
            'video'   => $video,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Testimoni berhasil dikirim'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi error server'
        ], 500);
    }
}

    public function update(Request $request, $id)
    {
        $testimoni = Testimoni::findOrFail($id);

        // CUSTOMER hanya boleh edit miliknya
        if (auth()->user()->role === 'customer' && $testimoni->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
            'media'   => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ]);

        $testimoni->rating  = $request->rating;
        $testimoni->content = $request->content;

        if ($request->hasFile('media')) {
            if ($testimoni->photo) Storage::disk('public')->delete($testimoni->photo);
            if ($testimoni->video) Storage::disk('public')->delete($testimoni->video);

            $file = $request->file('media');
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $testimoni->photo = $file->store('testimoni/photos', 'public');
                $testimoni->video = null;
            } else {
                $testimoni->video = $file->store('testimoni/videos', 'public');
                $testimoni->photo = null;
            }
        }

        $testimoni->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $testimoni = Testimoni::findOrFail($id);

        // CUSTOMER hanya boleh hapus miliknya
        if (auth()->user()->role === 'customer' && $testimoni->user_id !== auth()->id()) {
            abort(403);
        }

        if ($testimoni->photo) Storage::disk('public')->delete($testimoni->photo);
        if ($testimoni->video) Storage::disk('public')->delete($testimoni->video);

        $testimoni->delete();

        return response()->json(['success' => true]);
    }
}
