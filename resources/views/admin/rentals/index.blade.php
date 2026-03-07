@extends('layouts.admin')

@section('title', 'Admin Dashboard - HK Equipment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/rentals/index.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-main-wrapper">
    {{-- 01. MODERN INDUSTRIAL HEADER --}}
    <div class="hk-header-banner animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="hk-brand-info">
                <div class="hk-badge-active"><i class="bi bi-broadcast"></i> SYSTEM ONLINE</div>
                <h2 class="hk-main-title">RENTAL <span class="text-warning">MONITOR</span></h2>
                <p class="hk-main-subtitle">Panel kendali pusat untuk manajemen armada dan transaksi pelanggan.</p>
            </div>
            <div class="hk-header-btn">
                <a href="{{ route('admin.rentals.create') }}" class="btn-hk-action">
                    <i class="bi bi-plus-lg"></i> ENTRY MANUAL
                </a>
            </div>
        </div>
    </div>

    {{-- 02. SYSTEM TOOLBAR --}}
    <div class="hk-toolbar mt-3 animate__animated animate__fadeIn">
        <div class="hk-search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" id="hk-live-search" placeholder="Cari Pelanggan/Alat..." autocomplete="off">
        </div>
        
        <div class="hk-filter-wrapper">
            <form action="{{ route('admin.rentals.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="hk-select-pill" onchange="this.form.submit()">

                    <option value=""
                        {{ request('status') == '' ? 'selected' : '' }}>
                        STATUS: SEMUA
                    </option>

                    <option value="waiting_payment"
                        {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>
                        MENUNGGU PEMBAYARAN
                    </option>

                    <option value="paid"
                        {{ request('status') == 'paid' ? 'selected' : '' }}>
                        DIBAYAR
                    </option>

                    <option value="approved"
                        {{ request('status') == 'approved' ? 'selected' : '' }}>
                        DISETUJUI
                    </option>

                    <option value="on_progress"
                        {{ request('status') == 'on_progress' ? 'selected' : '' }}>
                        SEDANG BERJALAN
                    </option>

                    <option value="completed"
                        {{ request('status') == 'completed' ? 'selected' : '' }}>
                        SELESAI
                    </option>

                    <option value="cancelled"
                        {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        DIBATALKAN
                    </option>

                </select>

            </form>
        </div>
    </div>

    {{-- 03. MASTER DATA TABLE --}}
    <div class="hk-table-card mt-3 animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table align-middle hk-grid-table" id="rentalTable">
                <thead>
                    <tr>
                        <th class="text-center" width="50">NO</th>
                        <th>DATA PELANGGAN</th>
                        <th>UNIT ALAT</th>
                        <th>JADWAL & DURASI</th>
                        <th>LOKASI & CATATAN</th>
                        <th class="text-center">STATUS & STRUK</th>
                        <th class="text-end pe-4">SISTEM KONTROL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $index => $r)
                    <tr class="hk-tr-item" data-search="{{ strtolower($r->user->name . ' ' . $r->equipment->name . ' ' . $r->location) }}">
                        {{-- NOMOR URUT DENGAN DESAIN TEGAS --}}
                        <td class="text-center">
                            <div class="hk-no-circle">{{ $index + 1 }}</div>
                        </td>
                        
                        {{-- DATA PELANGGAN --}}
                        <td class="hk-col-user">
                            <div class="hk-user-name">{{ $r->user->name }}</div>
                            <a href="https://wa.me/{{ $r->user->phone }}" target="_blank" class="hk-user-phone">
                                <i class="bi bi-whatsapp"></i> {{ $r->user->phone }}
                            </a>
                        </td>

                        {{-- DATA UNIT (CLICKABLE IMAGE) --}}
                        <td class="hk-col-unit">
                            <div class="hk-unit-link" onclick="previewUnit('{{ asset('uploads/equipment/'.$r->equipment->image) }}', '{{ $r->equipment->name }}')">
                                <span class="fw-bold">{{ $r->equipment->name }}</span>
                                <i class="bi bi-image"></i>
                            </div>
                            <div class="hk-unit-total">Rp {{ number_format($r->total_price, 0, ',', '.') }}</div>
                        </td>

                        {{-- JADWAL --}}
                        <td class="hk-col-time">
                            <div class="fw-bold"><i class="bi bi-calendar3 me-1"></i> {{ date('d/m/Y', strtotime($r->rent_date)) }}</div>
                            <div class="text-muted small">{{ $r->duration_hours }} Jam • {{ $r->start_time }} WIB</div>
                        </td>

                        {{-- LOKASI & CATATAN --}}
                        <td class="hk-col-loc">
                            <div class="hk-loc-box"><i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($r->location, 60) }}</div>
                            <div class="hk-note-box text-danger">
                                Catatan: <span class="notes">"{{ $r->notes ?? '-' }}"</span>
                            </div>
                        </td>

                       {{-- STATUS & BUKTI (GABUNGAN) --}}
                        <td class="text-center">
                            <span class="hk-pill status-{{ $r->status }} mb-2">
                                {{ strtoupper(str_replace('_',' ', $r->status)) }}
                            </span>
                            
                            @if(!empty($r->payment_proof))
                            <div class="hk-proof-mini"
                                onclick="previewProof('{{ asset('storage/'.$r->payment_proof) }}')">

                                <i class="bi bi-receipt"></i>
                                LIHAT STRUK

                            </div>

                            @endif
                        </td>

                        {{-- KONTROL --}}
                        <td class="text-end pe-4">
                            <div class="hk-ctrl-group">
                                <form method="POST" action="{{ route('admin.rentals.status', $r->id) }}" class="d-inline">
                                    @csrf
                                    <select name="status" class="hk-status-dropdown" onchange="this.form.submit()">
                                        <option value="waiting_payment" {{ $r->status=='waiting_payment'?'selected':'' }}>Tunggu Bayar</option>
                                        <option value="paid" {{ $r->status=='paid'?'selected':'' }}>Dibayar</option>
                                        <option value="approved" {{ $r->status=='approved'?'selected':'' }}>Setuju</option>
                                        <option value="on_progress" {{ $r->status=='on_progress'?'selected':'' }}>Berjalan</option>
                                        <option value="completed" {{ $r->status=='completed'?'selected':'' }}>Selesai</option>
                                        <option value="cancelled" {{ $r->status=='cancelled'?'selected':'' }}>Dibatalkan</option>
                                    </select>
                                </form>
                                <a href="{{ route('admin.rentals.edit', $r->id) }}" class="hk-icon-btn btn-edit"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.rentals.destroy', $r->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="hk-icon-btn btn-del" onclick="confirmDelete(this.form)"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">TIDAK ADA DATA TRANSAKSI</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/rentals/index.js') }}"></script>
@endpush