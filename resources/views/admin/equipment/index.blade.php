@extends('layouts.admin')
@section('title', 'HK System - Fleet Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/equipment/index.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-main-wrapper">
    {{-- 01. HEADER BANNER --}}
    <div class="hk-header-banner animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="hk-brand-info">
                <div class="hk-badge-active"><i class="bi bi-broadcast"></i> SYSTEM ONLINE</div>
                <h2 class="hk-main-title">MANAJEMEN <span class="text-warning">ARMADA</span></h2>
                <p class="hk-main-subtitle">Panel kendali pusat untuk operasional unit alat berat Anda.</p>
            </div>
            <div class="hk-header-btn">
                <a href="{{ route('admin.equipment.create') }}" class="btn-hk-action">
                    <i class="bi bi-plus-lg"></i> REGISTRASI UNIT
                </a>
            </div>
        </div>
    </div>

    {{-- 02. SYSTEM TOOLBAR --}}
    <div class="hk-toolbar mt-3 animate__animated animate__fadeIn">
        <div class="hk-search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" id="hkSearchInput" placeholder="Cari nama, merk, atau ID armada..." autocomplete="off">
        </div>
        
        <div class="hk-filter-wrapper d-flex gap-2">
            <select id="hkCategoryFilter" class="hk-select-pill">
                <option value="">JENIS: SEMUA</option>
                <option value="Excavator">EXCAVATOR</option>
                <option value="Excavator Breaker">EXCAVATOR BREAKER</option>
            </select>
            <select id="hkStatusFilter" class="hk-select-pill">
                <option value="">STATUS: SEMUA</option>
                <option value="available">READY / TERSEDIA</option>
                <option value="rented">RENTED / TERSEWA</option>
                <option value="maintenance">MAINTENANCE</option>
            </select>
            <button onclick="location.reload()" class="btn-hk-refresh-small">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>

    {{-- 03. MASTER DATA TABLE --}}
    <div class="hk-table-card mt-3 animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table align-middle hk-grid-table" id="hkEquipmentTable">
                <thead>
                    <tr>
                        <th class="text-center" width="60">NO</th>
                        <th width="120">PREVIEW</th>
                        <th>UNIT IDENTITY</th>
                        <th>TECHNICAL SPECS</th>
                        <th>RENTAL RATE</th>
                        <th class="text-center">STATUS CONTROL</th>
                        <th class="text-end pe-4">SISTEM KONTROL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                    <tr class="hk-tr-item" 
                        data-name="{{ strtolower($item->name) }}"
                        data-brand="{{ strtolower($item->brand) }}"
                        data-id="id-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                        data-category="{{ $item->category }}"
                        data-status="{{ $item->status }}">
                        
                        <td class="text-center" data-label="No.">
                            <div class="hk-no-circle">{{ $loop->iteration }}</div>
                        </td>

                        <td class="hk-col-preview" data-label="Foto Unit">
                            <div class="hk-media-container" onclick="previewImage('{{ $item->image ? asset('uploads/equipment/'.$item->image) : '' }}','{{ $item->name }}')">
                                @if($item->image)
                                    <img src="{{ asset('uploads/equipment/'.$item->image) }}" class="hk-img-fit">
                                    <div class="hk-zoom-overlay"><i class="bi bi-zoom-in"></i></div>
                                @else
                                    <div class="hk-placeholder"><i class="bi bi-camera"></i></div>
                                @endif
                            </div>
                        </td>

                        <td data-label="Identitas">
                            <div class="hk-unit-main-info">
                                <div class="hk-name-text text-uppercase">{{ $item->name }}</div>
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge-id">ID-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <span class="badge-cat">{{ $item->category }}</span>
                                </div>
                            </div>
                        </td>

                        <td data-label="Spesifikasi">
                            <div class="hk-spec-stack">
                                <div class="hk-spec-row">
                                    <span class="label">Brand:</span>
                                    <span class="val-amber">{{ $item->brand ?? '-' }}</span>
                                </div>
                                <div class="hk-spec-row">
                                    <span class="label">Tahun:</span>
                                    <span class="val-black">{{ $item->year ?? '-' }}</span>
                                </div>
                                
                                @if(strlen($item->description) > 35)
                                    <div class="hk-spec-desc clickable-desc mt-1" onclick="expandDesc(this)" data-full="{{ $item->description }}">
                                        <span class="desc-text text-blue-soft">{{ Str::limit($item->description, 25) }}</span>
                                        <small class="hk-click-hint">🔍</small>
                                    </div>
                                @else
                                    <div class="hk-spec-desc text-blue-soft mt-1">
                                        {{ $item->description ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td data-label="Tarif Sewa">
                            <div class="hk-price-badge">
                                <span class="currency">Rp&nbsp;</span>
                                <span class="amount">{{ number_format($item->price_per_hour, 0, ',', '.') }}</span>
                                <span class="period">/Jam</span>
                            </div>
                        </td>

                        <td class="text-center" data-label="Status">
                            <form method="POST" action="{{ route('admin.equipment.updateStatus', $item->id) }}" class="w-100 hk-status-form">
                                @csrf @method('PATCH')
                                <select name="status" class="hk-status-dropdown st-{{ $item->status }} mb-1" onchange="toggleMaintenanceInput(this, {{ $item->id }})">
                                    <option value="available" {{ $item->status == 'available' ? 'selected' : '' }}>Ready</option>
                                    <option value="rented" {{ $item->status == 'rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="maintenance" {{ $item->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>

                                <div id="maint-input-{{ $item->id }}" class="maint-box-wrapper {{ $item->status == 'maintenance' ? '' : 'd-none' }}">
                                    <input type="date" name="maintenance_end_at" class="hk-maint-input" 
                                           value="{{ $item->maintenance_end_at ? \Carbon\Carbon::parse($item->maintenance_end_at)->format('Y-m-d') : '' }}"
                                           onchange="confirmUpdateStatus(this.form)">
                                </div>
                            </form>
                        </td>

                        <td class="text-end pe-4" data-label="Aksi">
                            <div class="hk-ctrl-group">
                                <a href="{{ route('admin.equipment.edit', $item->id) }}" class="hk-btn-action-mobile btn-edit">
                                    <i class="bi bi-pencil-square"></i> <span class="btn-text">EDIT</span>
                                </a>
                                <form action="{{ route('admin.equipment.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="hk-btn-action-mobile btn-del" onclick="confirmDelete(this.form, '{{ $item->name }}')">
                                        <i class="bi bi-trash3-fill"></i> <span class="btn-text">HAPUS</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">TIDAK ADA DATA ARMADA</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW IMAGE --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content hk-modal-dark border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-white" id="modalUnitName">Preview Unit</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modalPreviewImg" src="" class="img-fluid rounded-4">
            </div>
        </div>
    </div>
</div>

{{-- NOTIFIKASI SUKSES DARI SESSION --}}
@if(session('success'))
<script>
    window.addEventListener('load', function() {
        // Panggil fungsi helper yang sudah diperbaiki di atas
        swalSuccess("{{ session('success') }}");
    });
</script>
@endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/sweetalert/swal-helper.js') }}"></script>
    <script src="{{ asset('assets/js/admin/equipment/index.js') }}"></script>
@endpush