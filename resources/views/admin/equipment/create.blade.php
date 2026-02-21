@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin/equipment/create.css') }}">

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <div class="header-banner shadow-sm mb-4">
                <div class="d-flex align-items-center justify-content-between p-4 p-md-5 text-white">
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="fas fa-tools me-2"></i> Tambah Armada Baru
                        </h2>
                        <p class="mb-0 opacity-75">Manajemen inventaris alat berat dengan sistem integrasi data.</p>
                    </div>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-light btn-back fw-bold px-4 shadow-sm">
                        <i class="fas fa-list-ul me-2"></i>Daftar Alat
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.equipment.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm card-colorful h-100">
                            <div class="card-header bg-white border-0 pt-4 px-4">
                                <h5 class="fw-bold text-primary-dark mb-0">Detail Spesifikasi</h5>
                            </div>
                            <div class="card-body p-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger custom-alert">
                                        <ul class="mb-0 small">
                                            @foreach ($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="form-label-custom">Nama Alat Berat <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg custom-input" placeholder="Masukkan nama unit..." value="{{ old('name') }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label-custom">Kategori Unit</label>
                                        <div class="input-group-custom">
                                            <select name="category" class="form-select custom-select-colorful" required>
                                                <option value="" hidden>Pilih Kategori</option>
                                                @foreach(['Excavator', 'Excavator Breaker'] as $cat)
                                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label-custom">Merk / Brand</label>
                                        <input type="text" name="brand" class="form-control custom-input" placeholder="Komatsu, Caterpillar, Hitachi..." value="{{ old('brand') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label-custom">Tarif Sewa (Per Jam)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white border-0">Rp</span>
                                            <input type="number" name="price_per_hour" class="form-control custom-input-price" value="{{ old('price_per_hour') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label-custom">Tahun Unit</label>
                                        <input type="number" name="year" class="form-control custom-input" placeholder="Contoh: 2022" value="{{ old('year') }}">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label-custom text-dark">Catatan & Deskripsi</label>
                                    <textarea name="description" class="form-control custom-input" rows="4" placeholder="Berikan deskripsi singkat mengenai kondisi alat...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm card-sidebar mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-4">Foto Dokumentasi</h5>
                                <div class="upload-area shadow-inner" id="drop-area">
                                    <div class="upload-placeholder text-center" id="placeholder-content">
                                        <div class="icon-circle mb-3 mx-auto">
                                            <i class="fas fa-camera fa-2x text-primary"></i>
                                        </div>
                                        <p class="small text-muted mb-0">Seret foto ke sini atau <strong>klik</strong> untuk mencari</p>
                                    </div>
                                    <img id="image-preview" src="#" alt="Preview" class="d-none img-fluid rounded-3">
                                    <input type="file" name="image" id="image" class="d-none" accept="image/*">
                                </div>
                                <div class="alert alert-info mt-3 border-0 small">
                                    Format: <strong>JPG, PNG</strong> <br> Maksimal Ukuran: <strong>2MB</strong>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm bg-primary-dark text-white">
                            <div class="card-body p-4">
                                <button type="submit" class="btn btn-warning w-100 fw-bold py-3 mb-3 shadow-sm btn-save">
                                    <i class="fas fa-paper-plane me-2"></i>SIMPAN UNIT
                                </button>
                                <button type="reset" class="btn btn-outline-light w-100 btn-sm opacity-75">
                                    Reset Form
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/equipment/create.js') }}"></script>
@endpush