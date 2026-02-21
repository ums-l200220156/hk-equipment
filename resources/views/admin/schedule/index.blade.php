@extends('layouts.admin')
@section('title', 'Manajemen Jadwal Armada - HK Equipment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/schedule/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="hk-schedule-wrapper">
    {{-- Header Banner --}}
    <div class="hk-banner animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="hk-badge"><i class="bi bi-calendar-check"></i> MONITORING SISTEM</span>
                <h2 class="hk-title">KONTROL <span class="text-warning">JADWAL ARMADA</span></h2>
                <p class="hk-subtitle">Kelola alokasi waktu unit excavator dan alat berat secara real-time.</p>
            </div>
            <div class="col-md-5 text-md-end">
                <button id="btnHeatmap" class="btn-hk-heatmap">
                    <i class="bi bi-bar-chart-steps"></i> ANALISIS INTENSITAS
                </button>
            </div>
        </div>
    </div>

    {{-- Filter & Kontrol --}}
    <div class="hk-filter-card mt-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <label class="hk-label-sm">Filter Berdasarkan Unit</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-filter-square"></i></span>
                    <select id="equipmentFilter" class="form-select border-start-0">
                        <option value="">Semua Armada HK</option>
                        @foreach($equipment as $e)
                            <option value="{{ $e->id }}">{{ $e->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-8 text-md-end pt-md-4">
                <span class="badge bg-primary px-3 py-2"><i class="bi bi-info-circle"></i> Geser (Drag) kotak jadwal untuk mengubah waktu</span>
            </div>
        </div>
    </div>

    {{-- Kalender Utama --}}
    <div class="hk-calendar-container mt-4 animate__animated animate__fadeInUp">
        <div id="calendar"></div>
    </div>

    {{-- Section Heatmap --}}
    <div id="heatmapSection" class="hk-heatmap-card mt-4 d-none animate__animated animate__fadeInUp">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h5 class="m-0 fw-bold"><i class="bi bi-fire text-danger"></i> ANALISIS PRODUKTIVITAS ARMADA</h5>
            
            <div class="d-flex align-items-center gap-2">
                <label class="small fw-bold text-muted text-nowrap">Filter Periode:</label>
                <select id="heatmapPeriod" class="form-select form-select-sm border-warning" style="width: 160px; border-radius: 10px;">
                    <option value="all">Semua Waktu</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                    <option value="year">Tahun Ini</option>
                </select>
                <button class="btn-close ms-2" onclick="toggleHeatmap()"></button>
            </div>
        </div>
        <div id="heatmapContent"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Token CSRF untuk keamanan Laravel
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const UPDATE_ROUTE = '{{ route("admin.schedule.update") }}';
        const HEATMAP_ROUTE = '{{ route("admin.schedule.heatmap") }}';
        const EVENTS_FETCH_URL = '/admin/schedule/events';
    </script>
    <script src="{{ asset('assets/js/admin/schedule/index.js') }}"></script>
@endpush