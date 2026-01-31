@extends('layouts.base')

@section('title', 'Detail Penyewaan')

@section('content')
<section class="section-padding bg-light">
    <div class="container">

        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h6 class="text-danger fw-bold text-uppercase">Detail Transaksi</h6>
            <h2 class="fw-bold">Detail Penyewaan #{{ $rental->id }}</h2>
            <p class="text-muted mt-2">
                Informasi lengkap terkait penyewaan alat berat Anda
            </p>
            <div class="mx-auto mt-3" style="width:60px;height:4px;background:#dc3545;"></div>
        </div>

        {{-- BACK --}}
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('customer.rentals') }}"
               class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            {{-- ❌ BATALKAN SEWA --}}
            @if(in_array($rental->status,['waiting_payment','paid']))
                <form method="POST"
                      action="{{ route('customer.rentals.cancel',$rental->id) }}"
                      onsubmit="return confirm('Yakin ingin membatalkan penyewaan ini?')">
                    @csrf
                    <button class="btn btn-outline-danger rounded-pill px-4">
                        ❌ Batalkan Penyewaan
                    </button>
                </form>
            @endif
        </div>

        <div class="row g-4">

            {{-- INFO ALAT --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-truck me-2 text-danger"></i> Informasi Alat
                        </h5>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Nama</td>
                                <td class="fw-semibold">{{ $rental->equipment->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kategori</td>
                                <td>{{ $rental->equipment->category }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga / Jam</td>
                                <td class="fw-bold text-danger">
                                    Rp {{ number_format($rental->equipment->price_per_hour,0,',','.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- DETAIL SEWA --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-calendar-check me-2 text-danger"></i> Detail Sewa
                        </h5>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Tanggal</td>
                                <td>{{ \Carbon\Carbon::parse($rental->rent_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jam Mulai</td>
                                <td>{{ $rental->start_time }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Durasi</td>
                                <td>{{ $rental->duration_hours }} Jam</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Lokasi</td>
                                <td>{{ $rental->location }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TOTAL + STATUS --}}
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 d-flex justify-content-between flex-wrap gap-3">
                        <div>
                            <h6 class="text-muted">Total Harga</h6>
                            <h3 class="fw-bold text-danger">
                                Rp {{ number_format($rental->total_price,0,',','.') }}
                            </h3>
                        </div>

                        <div>
                            <h6 class="text-muted">Status</h6>

                            @php
                                $statusColor = match($rental->status){
                                    'waiting_payment' => 'warning',
                                    'paid' => 'info',
                                    'approved' => 'primary',
                                    'on_progress' => 'success',
                                    'completed' => 'dark',
                                    'cancelled' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp

                            <span class="badge bg-{{ $statusColor }} px-4 py-2 rounded-pill">
                                {{ strtoupper(str_replace('_',' ',$rental->status)) }}
                            </span>
                        </div>
                    </div>

                    {{-- ================= OVERTIME ================= --}}
                    <div class="card-footer bg-white border-0 px-4 pb-4">

                        {{-- AJUKAN OVERTIME --}}
                        @if(
                            in_array($rental->status,['on_progress','completed']) &&
                            $rental->overtime_hours > 0 &&
                            !$rental->overtime
                        )
                            <form method="POST"
                                  action="{{ route('customer.overtime.store',$rental->id) }}">
                                @csrf
                                <button class="btn btn-danger rounded-pill">
                                    ⏱️ Ajukan Overtime ({{ $rental->overtime_hours }} Jam)
                                </button>
                            </form>
                        @endif

                        {{-- INFO OVERTIME --}}
                        @if($rental->overtime)
                            <div class="alert alert-info mt-3 rounded-4">
                                <strong>⏱️ Overtime:</strong>
                                {{ $rental->overtime->extra_hours }} Jam <br>
                                Status: <b>{{ ucfirst($rental->overtime->status) }}</b>

                                <div class="mt-3 d-flex gap-2 flex-wrap">

                                    {{-- ❌ BATALKAN OVERTIME --}}
                                    @if($rental->overtime->status === 'pending')
                                        <form method="POST"
                                              action="{{ route('customer.overtime.cancel',$rental->overtime->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm rounded-pill">
                                                ❌ Batalkan Overtime
                                            </button>
                                        </form>
                                    @endif

                                    {{-- 💬 WHATSAPP --}}
                                    <a target="_blank"
                                       href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin, saya ingin menanyakan pengajuan overtime untuk penyewaan #' . $rental->id) }}"
                                       class="btn btn-success btn-sm rounded-pill">
                                        💬 Hubungi Admin
                                    </a>

                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
