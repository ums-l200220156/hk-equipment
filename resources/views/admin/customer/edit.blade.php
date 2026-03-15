@extends('layouts.admin')
@section('title', 'HK System - Edit Pelanggan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/customer/edit.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-form-wrapper">
    {{-- 01. HEADER NAVIGATION --}}
    <div class="hk-header-banner animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="hk-brand-info">
                <div class="hk-badge-active"><i class="bi bi-pencil-square"></i> MODIFIKASI DATA MITRA</div>
                <h2 class="hk-main-title">EDIT <span class="text-warning">PELANGGAN</span></h2>
                <p class="hk-main-subtitle">Memperbarui informasi profil dan detail kontak pelanggan.</p>
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
        <form id="hkCustomerEditForm" method="POST" action="{{ route('admin.customer.update', $customer->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                {{-- KOLOM KIRI: IDENTITAS --}}
                <div class="col-lg-6">
                    <div class="hk-form-section">
                        <h5 class="hk-section-title"><i class="bi bi-person-lines-fill"></i> Profil Utama</h5>
                        
                        <div class="mb-3">
                            <label class="hk-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="hk-input-group">
                                <i class="bi bi-person hk-icon"></i>
                                <input type="text" name="name" class="hk-input @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $customer->name) }}" placeholder="Nama pelanggan..." required>
                            </div>
                            @error('name') <div class="hk-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Alamat Email <span class="text-danger">*</span></label>
                            <div class="hk-input-group">
                                <i class="bi bi-envelope-at hk-icon"></i>
                                <input type="email" name="email" class="hk-input @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $customer->email) }}" placeholder="email@perusahaan.com" required>
                            </div>
                            @error('email') <div class="hk-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Nomor Telepon</label>
                            <div class="hk-input-group">
                                <i class="bi bi-telephone-outbound hk-icon"></i>
                                <input type="text" name="phone" class="hk-input @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone', $customer->phone) }}" placeholder="62812XXXXXX" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DETAIL --}}
                <div class="col-lg-6">
                    <div class="hk-form-section">
                        <h5 class="hk-section-title"><i class="bi bi-geo-alt"></i> Detail Lokasi & Catatan</h5>

                        <div class="mb-3">
                            <label class="hk-label">Alamat Lengkap</label>
                            <div class="hk-input-group align-items-start">
                                <i class="bi bi-map hk-icon mt-2"></i>
                                <textarea name="address" class="hk-input hk-textarea" 
                                    rows="3" placeholder="Alamat lengkap..." required>{{ old('address', $customer->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="hk-label">Catatan Admin (Opsional)</label>
                            <div class="hk-input-group align-items-start">
                                <i class="bi bi-sticky hk-icon mt-2"></i>
                                <textarea name="notes" class="hk-input hk-textarea" 
                                    rows="3" placeholder="Tambahkan catatan khusus...">{{ old('notes', $customer->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 03. ACTION BUTTONS --}}
            <div class="hk-form-actions mt-4 border-top pt-4">
                <button type="submit" class="hk-btn-update">
                    <i class="bi bi-check2-circle"></i> SIMPAN PERUBAHAN DATA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/customer/edit.js') }}"></script>
@endpush