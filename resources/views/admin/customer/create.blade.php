@extends('layouts.admin')
@section('title', 'HK System - Tambah Pelanggan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/customer/create.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-form-wrapper">
    {{-- 01. HEADER NAVIGATION --}}
    <div class="hk-header-banner animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="hk-brand-info">
                <div class="hk-badge-active"><i class="bi bi-person-plus-fill"></i> REGISTRASI MITRA BARU</div>
                <h2 class="hk-main-title">TAMBAH <span class="text-warning">PELANGGAN</span></h2>
                <p class="hk-main-subtitle">Input data pelanggan baru ke dalam sistem database HK Equipment.</p>
            </div>
            <div class="hk-header-btn">
                <a href="{{ route('admin.customer.index') }}" class="btn-hk-back">
                    <i class="bi bi-arrow-left"></i> KEMBALI KE DAFTAR
                </a>
            </div>
        </div>
    </div>

    {{-- 02. FORM CARD --}}
    <div class="hk-card-form mt-4 animate__animated animate__fadeInUp">
        <form id="hkCustomerForm" method="POST" action="{{ route('admin.customer.store') }}">
            @csrf
            
            <div class="row g-4">
                {{-- KOLOM KIRI: IDENTITAS DASAR --}}
                <div class="col-lg-6">
                    <div class="hk-form-section">
                        <h5 class="hk-section-title"><i class="bi bi-person-badge"></i> Informasi Identitas</h5>
                        
                        <div class="mb-3">
                            <label class="hk-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="hk-input-group">
                                <i class="bi bi-person hk-icon"></i>
                                <input type="text" name="name" class="hk-input @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" placeholder="Contoh: Alfian Candra" required>
                            </div>
                            @error('name') <div class="hk-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Alamat Email <span class="text-danger">*</span></label>
                            <div class="hk-input-group">
                                <i class="bi bi-envelope hk-icon"></i>
                                <input type="email" name="email" class="hk-input @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="email@perusahaan.com" required>
                            </div>
                            <small class="hk-hint">Email ini akan digunakan untuk akses Login.</small>
                            @error('email') <div class="hk-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Password Akses <span class="text-danger">*</span></label>
                            <div class="hk-input-group">
                                <i class="bi bi-shield-lock hk-icon"></i>
                                <input type="password" id="password" name="password" class="hk-input" 
                                    placeholder="Min. 8 Karakter" minlength="8" required>
                                <button type="button" class="hk-toggle-btn" id="togglePassword">
                                    <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Nomor Telepon / WhatsApp</label>
                            <div class="hk-input-group">
                                <i class="bi bi-whatsapp hk-icon"></i>
                                <input type="text" name="phone" class="hk-input" 
                                    value="{{ old('phone') }}" placeholder="0812XXXXXX">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: LOKASI & CATATAN --}}
                <div class="col-lg-6">
                    <div class="hk-form-section">
                        <h5 class="hk-section-title"><i class="bi bi-geo-alt"></i> Detail Lokasi & Catatan</h5>

                        <div class="mb-3">
                            <label class="hk-label">Alamat Lengkap Domisili</label>
                            <div class="hk-input-group align-items-start">
                                <i class="bi bi-map hk-icon mt-2"></i>
                                <textarea name="address" class="hk-input hk-textarea" 
                                    rows="4" placeholder="Masukkan alamat lengkap pelanggan...">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Catatan Admin (Internal)</label>
                            <div class="hk-input-group align-items-start">
                                <i class="bi bi-pencil-square hk-icon mt-2"></i>
                                <textarea name="notes" class="hk-input hk-textarea" 
                                    rows="4" placeholder="Catatan khusus pelanggan ini...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 03. ACTION BUTTONS --}}
            <div class="hk-form-actions mt-4 border-top pt-4">
                <button type="submit" class="hk-btn-submit">
                    <i class="bi bi-save2"></i> SIMPAN DATA PELANGGAN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/sweetalert/swal-helper.js') }}"></script>
    <script src="{{ asset('assets/js/admin/customer/create.js') }}"></script>
@endpush