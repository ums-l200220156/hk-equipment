@extends('layouts.admin')

@section('content')
<div class="container py-5">

    <header class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="fw-bolder text-dark">
            <i class="fas fa-plus-circle text-primary-custom me-2"></i> Tambah Alat Berat Baru
        </h2>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </header>

    <div class="card custom-card shadow-lg p-3">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading fs-5"><i class="fas fa-exclamation-triangle me-2"></i> Terjadi Kesalahan!</h4>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.equipment.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(['Excavator', 'Excavator Breaker','Dump Truck'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Sewa per Jam (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price_per_hour" class="form-control" value="{{ old('price_per_hour') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun Produksi</label>
                            <input type="number" name="year" class="form-control" placeholder="misal: 2020" value="{{ old('year') }}">
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="brand" class="form-control" placeholder="misal: Hitachi, Komatsu, CAT" value="{{ old('brand') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Alat</label>
                            <input type="file" name="image" class="form-control">
                            <small class="form-text text-muted">Maksimal 2MB, JPG/PNG.</small>
                        </div>

                    </div>
                </div>

                <div class="border-top pt-3 mt-3 text-end">
                    <button type="submit" class="btn btn-primary-custom fw-bold shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan Alat
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
