@extends('layouts.admin')
@section('title', 'HK System - Customer Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/customer/index.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-main-wrapper">
    {{-- 01. MODERN HEADER BANNER --}}
    <div class="hk-header-banner animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="hk-brand-info">
                <div class="hk-badge-active"><i class="bi bi-shield-check"></i> DATABASE PELANGGAN</div>
                <h2 class="hk-main-title">MANAJEMEN <span class="text-warning">PELANGGAN</span></h2>
                <p class="hk-main-subtitle">Pusat data mitra dan pelanggan setia HK Equipment.</p>
            </div>
            <div class="hk-header-btn">
                <a href="{{ route('admin.customer.create') }}" class="btn-hk-action">
                    <i class="bi bi-person-plus-fill"></i> TAMBAH PELANGGAN
                </a>
            </div>
        </div>
    </div>

    {{-- 02. SYSTEM TOOLBAR --}}
    <div class="hk-toolbar mt-4 animate__animated animate__fadeIn">
        <div class="hk-search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" id="hkSearchInput" placeholder="Cari nama, email, atau alamat..." autocomplete="off">
        </div>
        <div class="hk-filter-wrapper">
            <button onclick="location.reload()" class="btn-hk-refresh-small">
                <i class="bi bi-arrow-clockwise"></i> REFRESH DATA
            </button>
        </div>
    </div>

    {{-- 03. MASTER DATA TABLE --}}
    <div class="hk-table-card mt-3 animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table align-middle hk-grid-table" id="hkCustomerTable">
                <thead>
                    <tr>
                        <th class="text-center" width="60">NO</th>
                        <th>PROFIL PELANGGAN</th>
                        <th>KONTAK & EMAIL</th>
                        <th>DOMISILI / ALAMAT</th>
                        <th>CATATAN INTERNAL</th>
                        <th class="text-end pe-4">KONTROL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr class="hk-tr-item" 
                        data-name="{{ strtolower($c->name) }}"
                        data-email="{{ strtolower($c->email) }}"
                        data-address="{{ strtolower($c->address) }}">
                        
                        {{-- NO --}}
                        <td class="text-center" data-label="No.">
                            <div class="hk-no-circle">{{ $loop->iteration }}</div>
                        </td>

                        {{-- PROFIL --}}
                        <td data-label="Nama Pelanggan">
                            <div class="hk-user-info-box">
                                @if($c->image)
                                    <img src="{{ asset('uploads/users/'.$c->image) }}" 
                                        class="hk-avatar-mini" 
                                        style="object-fit: cover;"
                                        onclick="previewImage('{{ asset('uploads/users/'.$c->image) }}', '{{ $c->name }}')">
                                @else
                                    <div class="hk-avatar-mini">{{ strtoupper(substr($c->name, 0, 1)) }}</div>
                                @endif
                                <div class="hk-user-name text-uppercase">{{ $c->name }}</div>
                            </div>
                        </td>

                        {{-- KONTAK --}}
                        <td data-label="Kontak">
                            <div class="hk-contact-stack">
                                <a href="https://wa.me/{{ $c->phone }}" target="_blank" class="hk-phone-badge">
                                    <i class="bi bi-whatsapp"></i> {{ $c->phone }}
                                </a>
                                <div class="hk-email-text"><i class="bi bi-envelope"></i> {{ $c->email }}</div>
                            </div>
                        </td>

                        {{-- ALAMAT --}}
                        <td data-label="Alamat">
                            <div class="hk-address-box">
                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                <span>{{ Str::limit($c->address, 50) }}</span>
                            </div>
                        </td>

                        {{-- CATATAN --}}
                        <td data-label="Catatan">
                            <div class="hk-note-bubble {{ $c->notes ? '' : 'empty' }}">
                                {{ $c->notes ?? 'Tidak ada catatan.' }}
                            </div>
                        </td>

                        {{-- AKSI --}}
                        <td class="text-end pe-4" data-label="Sistem Kontrol">
                            <div class="hk-ctrl-group">
                                <a href="{{ route('admin.customer.edit', $c->id) }}" class="hk-btn-action-mobile btn-edit">
                                    <i class="bi bi-pencil-square"></i> <span class="btn-text">EDIT</span>
                                </a>
                                <form action="{{ route('admin.customer.delete', $c->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="hk-btn-action-mobile btn-del" onclick="confirmDelete(this.form, '{{ $c->name }}')">
                                        <i class="bi bi-trash3-fill"></i> <span class="btn-text">HAPUS</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">BELUM ADA DATA PELANGGAN</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- NOTIFIKASI --}}
@if(session('success'))
<script>
    window.addEventListener('load', function() {
        swalSuccess("{{ session('success') }}");
    });
</script>
@endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="{{ asset('assets/js/sweetalert/swal-helper.js') }}"></script>
    <script src="{{ asset('assets/js/admin/customer/index.js') }}"></script>
@endpush