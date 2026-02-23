@extends('layouts.base')

@section('title', 'Riwayat Penyewaan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/customer/rentals/index.css') }}">
@endpush

@section('content')
<section class="rental-history-section">
    {{-- 01. HEADER WRAPPER PREMIUM --}}
    <div class="header-wrapper-premium mb-4">
        <div class="container">
            <div class="header-main">
                <div class="header-text">
                    <h2 class="fw-black text-white">RIWAYAT <span class="text-accent">SEWA</span></h2>
                    <p class="text-white-50">Pantau operasional dan status penyewaan alat berat Anda secara real-time.</p>
                </div>
                <div class="header-icon d-none d-md-flex">
                    <div class="icon-circle shadow-lg truck-animation">
                        <i class="bi bi-truck-flatbed"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        {{-- 02. FILTER TAB --}}
        <div class="filter-container-desktop">
            <div class="filter-wrapper">
                <div class="filter-scroll">
                    <button class="filter-btn active" data-status="all">Semua</button>
                    <button class="filter-btn" data-status="waiting_payment">Menunggu</button>
                    <button class="filter-btn" data-status="paid">Dibayar</button>
                    <button class="filter-btn" data-status="on_progress">Berjalan</button>
                    <button class="filter-btn" data-status="completed">Selesai</button>
                    <button class="filter-btn" data-status="cancelled">Batal</button>
                </div>
            </div>
        </div>

        {{-- 03. MAIN CONTENT --}}
        <div class="main-card">
            @if($rentals->count() === 0)
                <div class="empty-state text-center py-5">
                    <i class="bi bi-clipboard-x display-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada riwayat penyewaan ditemukan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table responsive-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Unit Alat Berat</th>
                                <th>Jadwal Sewa</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Total Biaya</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $r)
                            <tr class="rental-row" data-status="{{ $r->status }}">
                                <td data-label="No." class="text-center fw-bold text-muted">
                                    {{ $loop->iteration }}
                                </td>

                                <td data-label="Unit">
                                    <div class="unit-box">
                                        <div class="unit-img-wrapper" onclick="previewImage('{{ asset('uploads/equipment/'.$r->equipment->image) }}', '{{ $r->equipment->name }}')">
                                            <img src="{{ asset('uploads/equipment/'.$r->equipment->image) }}" alt="Unit">
                                            <div class="img-overlay"><i class="bi bi-zoom-in"></i></div>
                                        </div>
                                        <div class="text-data">
                                            <span class="fw-bold text-dark">{{ $r->equipment->name }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="Jadwal">
                                    <div class="date-info text-data">
                                        <span class="fw-semibold d-block text-nowrap">{{ \Carbon\Carbon::parse($r->rent_date)->translatedFormat('d M Y') }}</span>
                                        {{-- FORMAT JAM & MENIT SAJA --}}
                                        <small class="text-muted d-block">Pukul: {{ date('H:i', strtotime($r->start_time)) }}</small>
                                    </div>
                                </td>

                                <td data-label="Durasi">
                                    <div class="text-data">
                                        <span class="tag-duration text-nowrap">{{ $r->duration_hours }} Jam</span>
                                    </div>
                                </td>

                                <td data-label="Status">
                                    <div class="text-data">
                                        @php
                                            $map = [
                                                'waiting_payment' => ['warning', 'Menunggu'],
                                                'paid' => ['success', 'Dibayar'],
                                                'on_progress' => ['primary', 'Berjalan'],
                                                'completed' => ['dark', 'Selesai'],
                                                'cancelled' => ['danger', 'Batal'],
                                            ];
                                            $st = $map[$r->status] ?? ['secondary', $r->status];
                                        @endphp
                                        <span class="badge-status status-{{ $st[0] }}">
                                            <span class="dot"></span> {{ $st[1] }}
                                        </span>
                                    </div>
                                </td>

                                <td data-label="Total">
                                    <div class="text-data">
                                        <span class="text-price text-danger text-nowrap">Rp {{ number_format($r->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </td>

                                <td data-label="Aksi">
                                    <div class="action-flex">
                                        <a href="{{ route('customer.rentals.show', $r->id) }}" class="btn-action-modern btn-view-modern" title="Detail">
                                            <i class="bi bi-eye"></i> <span class="btn-text">Detail</span>
                                        </a>

                                        @if($r->status === 'waiting_payment')
                                            <form method="POST" action="{{ route('customer.rentals.cancel', $r->id) }}" class="cancel-form">
                                                @csrf
                                                <button type="button" class="btn-action-modern btn-cancel-modern" onclick="confirmCancel(this)" title="Batalkan">
                                                    <i class="bi bi-trash3"></i> <span class="btn-text">Batal</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- MODAL PREVIEW (Tetap sama) --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="modalTitle">Unit Alat Berat</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <img id="imgPreviewSource" src="" class="img-fluid rounded-3 shadow-sm" alt="Preview">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/customer/rentals/index.js') }}"></script>
@endpush