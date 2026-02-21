@extends('layouts.admin')

@section('title', 'System Edit - Transaksi #' . $rental->id)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/rentals/edit.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-edit-container">
    <div class="hk-header-card animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rentals.index') }}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Update Data</li>
                    </ol>
                </nav>
                <h2 class="hk-page-title">MODIFIKASI <span class="text-warning">LOG SEWA</span></h2>
                <p class="hk-subtitle">Perubahan data operasional untuk unit: <strong>{{ $rental->equipment->name }}</strong></p>
            </div>
            <div class="hk-header-action">
                <a href="{{ route('admin.rentals.index') }}" class="btn-hk-back">
                    <i class="bi bi-arrow-left-circle-fill"></i> KEMBALI KE LIST
                </a>
            </div>
        </div>
    </div>

    <div class="hk-form-card mt-4 animate__animated animate__fadeInUp">
        <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST" id="editRentalForm">
            @csrf
            @method('PUT')

            {{-- SECTION 1: INTI TRANSAKSI --}}
            <div class="hk-section mb-4">
                <h5 class="hk-section-title"><i class="bi bi-person-gear"></i> Inti Transaksi</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="hk-input-group">
                            <label class="hk-label">Pelanggan (Read Only)</label>
                            <input type="text" class="form-control hk-input disabled" value="{{ $rental->user->name }} - {{ $rental->user->phone }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hk-input-group">
                            <label class="hk-label">Unit Alat Berat</label>
                            <select name="equipment_id" id="equipmentSelect" class="form-select hk-input" required>
                                @foreach($equipments as $eq)
                                    <option value="{{ $eq->id }}" data-price="{{ $eq->price_per_hour }}" {{ $rental->equipment_id == $eq->id ? 'selected' : '' }}>
                                        {{ $eq->name }} (Rp {{ number_format($eq->price_per_hour, 0, ',', '.') }}/Jam)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: PENJADWALAN --}}
            <div class="hk-section mb-4">
                <h5 class="hk-section-title"><i class="bi bi-calendar-range"></i> Penjadwalan & Biaya</h5>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="hk-input-group">
                            <label class="hk-label">Tanggal Sewa</label>
                            <input type="date" name="rent_date" class="form-control hk-input" value="{{ $rental->rent_date }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="hk-input-group">
                            <label class="hk-label">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control hk-input" value="{{ date('H:i', strtotime($rental->start_time)) }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="hk-input-group">
                            <label class="hk-label">Durasi (Jam)</label>
                            <input type="number" name="duration_hours" id="durationInput" class="form-control hk-input-warning" value="{{ $rental->duration_hours }}" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="hk-input-group">
                            <label class="hk-label">Estimasi Total</label>
                            <div class="hk-price-preview">
                                <span class="currency">Rp</span>
                                <span id="autoTotalPrice">{{ number_format($rental->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: LOKASI --}}
            <div class="hk-section">
                <h5 class="hk-section-title"><i class="bi bi-geo-alt"></i> Detail Lokasi Proyek</h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="hk-input-group">
                            <label class="hk-label">Alamat Lengkap Lokasi</label>
                            <input type="text" name="location" class="form-control hk-input" value="{{ $rental->location }}" placeholder="Contoh: Proyek Tol KM 45, Wonogiri" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hk-input-group">
                            <label class="hk-label">Catatan Tambahan</label>
                            <textarea name="notes" rows="4" class="form-control hk-input" minlength="5" maxlength="200" placeholder="Tambahkan instruksi khusus di sini...">{{ $rental->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hk-actions-area mt-5 pt-4 border-top">
                <div class="d-flex flex-column flex-md-row justify-content-end gap-3">
                    <button type="reset" class="btn btn-light btn-hk-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> RESET ULANG
                    </button>
                    <button type="submit" class="btn btn-warning btn-hk-submit">
                        <i class="bi bi-cloud-check-fill"></i> SIMPAN PERUBAHAN SISTEM
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/rentals/edit.js') }}"></script>
@endpush