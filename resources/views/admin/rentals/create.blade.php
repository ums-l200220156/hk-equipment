@extends('layouts.admin')

@section('title', 'HK System - Entry Transaction')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/rentals/create.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- SELECT2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="hk-create-wrapper">
    <div class="hk-header-card animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rentals.index') }}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Input Baru</li>
                    </ol>
                </nav>
                <h2 class="hk-page-title">ENTRY <span class="text-warning">NEW TRANSACTION</span></h2>
                <p class="hk-subtitle">Input data penyewaan manual untuk pelanggan offline.</p>
            </div>
            <div class="hk-header-action">
                <a href="{{ route('admin.rentals.index') }}" class="btn-hk-back">
                    <i class="bi bi-arrow-left-circle"></i> KEMBALI
                </a>
            </div>
        </div>
    </div>

    <div class="hk-form-card mt-4 animate__animated animate__fadeInUp">
        <form action="{{ route('admin.rentals.store') }}" method="POST" id="createRentalForm">
            @csrf

            {{-- SECTION 1: PELANGGAN & ALAT --}}
            <div class="hk-section mb-4">
                <h5 class="hk-section-title"><i class="bi bi-person-bounding-box"></i> Identifikasi</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="hk-input-group">
                            <label class="hk-label">Pilih Pelanggan</label>
                            <select name="user_id" id="userSelect" class="form-select hk-input" required>
                                <option value="" disabled selected>-- Ketik Nama atau No. HP Pelanggan --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hk-input-group">
                            <label class="hk-label">Unit Alat Berat</label>
                            <select name="equipment_id" id="equipmentSelect" class="form-select hk-input" required>
                                <option value="" disabled selected>-- Pilih Unit Tersedia --</option>
                                @foreach($equipments as $e)
                                    <option value="{{ $e->id }}" data-price="{{ $e->price_per_hour }}">
                                        {{ $e->name }} - Rp {{ number_format($e->price_per_hour, 0, ',', '.') }}/Jam
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: WAKTU & HARGA --}}
            <div class="hk-section mb-4">
                <h5 class="hk-section-title"><i class="bi bi-stopwatch"></i> Penjadwalan & Biaya</h5>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="hk-input-group">
                            <label class="hk-label">Tanggal Sewa</label>
                            <input 
                                type="date"
                                name="rent_date"
                                class="form-control hk-input"
                                value="{{ date('Y-m-d') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="hk-input-group">
                            <label class="hk-label">Jam Mulai</label>
                            <input 
                                type="time"
                                name="start_time"
                                class="form-control hk-input"
                                step="60"
                                required
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="hk-input-group">
                            <label class="hk-label">Durasi (Jam)</label>
                            <input type="number" name="duration_hours" id="durationInput" class="form-control hk-input-warning" min="1" placeholder="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="hk-input-group">
                            <label class="hk-label">Preview Total Biaya</label>
                            <div class="hk-price-preview">
                                <span class="currency">Rp</span>
                                <span id="totalPricePreview">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: LOKASI & CATATAN --}}
            <div class="hk-section">
                <h5 class="hk-section-title"><i class="bi bi-geo-alt"></i> Lokasi Proyek</h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="hk-input-group">
                            <label class="hk-label">Lokasi Pengerjaan</label>
                            <input type="text" name="location" class="form-control hk-input" placeholder="Misal: Proyek Waduk Gajah Mungkur, Wonogiri" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hk-input-group">
                            <label class="hk-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="form-control hk-input" rows="3" minlength="5" maxlength="200" placeholder="Tambahkan detail tambahan jika ada (5-200 karakter)..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hk-actions-area mt-5 pt-4 border-top">
                <div class="d-flex flex-column flex-md-row justify-content-end gap-3">
                    <button type="reset" id="btnReset" class="btn btn-light btn-hk-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> RESET DATA
                    </button>
                    <button type="submit" class="btn btn-warning btn-hk-submit">
                        <i class="bi bi-cloud-arrow-up-fill"></i> SIMPAN & PROSES TRANSAKSI
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/rentals/create.js') }}"></script>
@endpush