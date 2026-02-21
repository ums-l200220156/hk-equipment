@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin/equipment/edit.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid py-4 px-md-5 bg-industrial">
    <div class="row justify-content-center">
        <div class="col-xl-11">

            <div class="header-industrial shadow-lg mb-5">
                <div class="header-content p-4 p-md-5 text-white position-relative d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="header-text">
                        <span class="badge bg-warning text-dark mb-2 px-3 fw-bold">MODE EDIT</span>
                        <h1 class="fw-black mb-1 text-uppercase">UNIT ARMADA <span class="text-orange">#{{ $equipment->id }}</span></h1>
                        <p class="opacity-75 mb-0 d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i> Manajemen perbaikan data dan dokumentasi armada
                        </p>
                    </div>
                    <div class="header-action mt-4 mt-md-0">
                        <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                    <i class="fas fa-cogs gear-icon"></i>
                </div>
            </div>

            <form id="editForm" method="POST" action="{{ route('admin.equipment.update', $equipment->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    
                    <div class="col-lg-8">
                        <div class="card card-industrial border-0 shadow-sm mb-4">
                            <div class="card-header-accent d-flex align-items-center">
                                <div class="icon-box-sm me-3"><i class="fas fa-file-alt"></i></div>
                                <h5 class="mb-0 fw-bold">Informasi Teknis Unit</h5>
                            </div>
                            <div class="card-body p-4 p-md-5">
                                <div class="mb-4">
                                    <label class="label-industrial"><i class="fas fa-tag me-1"></i> Nama Alat Berat</label>
                                    <div class="input-group-industrial h-input-standard">
                                        <i class="fas fa-truck-monster"></i>
                                        <input type="text" name="name" value="{{ $equipment->name }}" placeholder="Masukkan nama unit..." required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="label-industrial"><i class="fas fa-layer-group me-1"></i> Kategori Armada</label>
                                        <div class="input-group-industrial h-input-standard">
                                            <i class="fas fa-list"></i>
                                            <select name="category" class="select-industrial" required>
                                                <option value="Excavator" {{ $equipment->category == 'Excavator' ? 'selected' : '' }}>Excavator</option>
                                                <option value="Excavator Breaker" {{ $equipment->category == 'Excavator Breaker' ? 'selected' : '' }}>Excavator Breaker</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="label-industrial"><i class="fas fa-money-bill-wave me-1"></i> Harga Sewa / Jam</label>
                                        <div class="input-group-industrial price-container h-input-standard">
                                            <span class="currency">Rp</span>
                                            <input type="number" name="price_per_hour" class="price-field" value="{{ (int)$equipment->price_per_hour }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="label-industrial"><i class="fas fa-industry me-1"></i> Manufaktur / Merk</label>
                                        <div class="input-group-industrial h-input-standard">
                                            <i class="fas fa-copyright"></i>
                                            <input type="text" name="brand" value="{{ $equipment->brand }}" placeholder="Contoh: KOMATSU">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="label-industrial"><i class="fas fa-calendar-alt me-1"></i> Tahun Rilis</label>
                                        <div class="input-group-industrial h-input-standard">
                                            <i class="fas fa-clock"></i>
                                            <input type="number" name="year" value="{{ $equipment->year }}" placeholder="YYYY">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="label-industrial"><i class="fas fa-align-left me-1"></i> Spesifikasi Detail</label>
                                    <textarea name="description" class="input-industrial" rows="4">{{ $equipment->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-industrial border-0 shadow-sm mb-4">
                            <div class="card-header-accent bg-dark text-white">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-camera me-2 text-warning"></i>Visual Unit</h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                <div class="image-stack mb-4 position-relative">
                                    <div class="image-label">FOTO SAAT INI</div>
                                    @if ($equipment->image)
                                        <img src="{{ asset('uploads/equipment/'.$equipment->image) }}" class="img-main rounded shadow-sm" id="old-img">
                                    @else
                                        <div class="no-image bg-light rounded border d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted opacity-25"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="upload-zone" id="upload-box">
                                    <input type="file" name="image" id="image-input" class="d-none" accept="image/*">
                                    <div class="upload-ui">
                                        <i class="fas fa-sync-alt text-orange mb-2 fa-lg"></i>
                                        <span class="fw-bold d-block small">Ganti Foto Unit</span>
                                    </div>
                                    <img id="preview-img" src="#" class="d-none rounded img-fluid shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="card card-action border-0 shadow-lg text-white">
                            <div class="card-body p-4 text-center">
                                <h4 class="fw-black mb-3 text-uppercase">Konfirmasi</h4>
                                <p class="small opacity-75 mb-4">Pastikan data yang diubah telah sesuai dengan kondisi fisik unit.</p>
                                <button type="submit" class="btn btn-warning btn-lg w-100 fw-black py-3 shadow-sm">
                                    SIMPAN PERUBAHAN <i class="fas fa-save ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/admin/equipment/edit.js') }}"></script>
@endsection