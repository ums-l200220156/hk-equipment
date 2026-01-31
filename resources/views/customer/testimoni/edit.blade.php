@extends('layouts.base')

@section('content')
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width:600px">
        <div class="card-body">
            <h4 class="fw-bold text-center mb-4">✏️ Edit Testimoni</h4>

            <form method="POST"
                  action="{{ route('testimoni.update', $testimoni->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label class="fw-bold">Rating</label>
                <select name="rating" class="form-select mb-3" required>
                    @for($i=5;$i>=1;$i--)
                        <option value="{{ $i }}"
                            {{ $testimoni->rating == $i ? 'selected' : '' }}>
                            {{ str_repeat('★', $i) }}
                        </option>
                    @endfor
                </select>

                <label class="fw-bold">Isi Testimoni</label>
                <textarea name="content"
                          class="form-control mb-3"
                          rows="4"
                          required>{{ $testimoni->content }}</textarea>

                <label class="fw-bold">Ganti Foto / Video (Opsional)</label>
                <input type="file" name="media" class="form-control mb-4">

                <button class="btn btn-danger w-100 fw-bold">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
