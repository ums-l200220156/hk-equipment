@extends('layouts.base')

@section('title', 'Riwayat Penyewaan')

@section('content')
<section class="section-padding bg-light">
    <div class="container">

        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h6 class="text-danger fw-bold text-uppercase">Akun Saya</h6>
            <h2 class="fw-bold">Riwayat Penyewaan Alat Berat</h2>
            <p class="text-muted mt-2">
                Daftar seluruh penyewaan alat berat yang pernah Anda lakukan
            </p>
            <div class="mx-auto mt-3" style="width:60px;height:4px;background:#dc3545;"></div>
        </div>

        {{-- CARD TABLE --}}
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                @if($rentals->count() === 0)
                    {{-- EMPTY STATE --}}
                    <div class="empty-state text-center py-5">
                        <i class="bi bi-truck fs-1 text-muted"></i>
                        <p class="mt-3">Anda belum pernah melakukan penyewaan.</p>
                    </div>
                @else

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">

                        <thead class="table-dark text-center">
                            <tr>
                                <th>NO</th>
                                <th>Alat Berat</th>
                                <th>Tanggal & Jam</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($rentals as $r)
                            <tr class="text-center">

                                {{-- NO URUT --}}
                                <td class="fw-bold">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- ALAT --}}
                                <td class="text-start">
                                    <div class="fw-semibold">
                                        {{ $r->equipment->name }}
                                    </div>
                                </td>

                                {{-- TANGGAL --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($r->rent_date)->format('d M Y') }}
                                    <div class="text-muted small">
                                        Jam {{ $r->start_time }}
                                    </div>
                                </td>

                                {{-- DURASI --}}
                                <td>
                                    {{ $r->duration_hours }} Jam
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @php
                                        $map = [
                                            'waiting_payment' => ['secondary','Menunggu Pembayaran'],
                                            'paid' => ['success','Sudah Dibayar'],
                                            'approved' => ['info','Disetujui'],
                                            'on_progress' => ['primary','Berjalan'],
                                            'completed' => ['dark','Selesai'],
                                            'cancelled' => ['danger','Dibatalkan'],
                                        ];
                                        @endphp

                                        <span class="badge rounded-pill bg-{{ $map[$r->status][0] ?? 'secondary' }} px-3 py-2">
                                            {{ $map[$r->status][1] ?? $r->status }}
                                        </span>

                                </td>

                                {{-- HARGA --}}
                                <td class="fw-bold text-danger">
                                    Rp {{ number_format($r->total_price, 0, ',', '.') }}
                                </td>

                                {{-- AKSI --}}
                                <td class="d-flex gap-2 justify-content-center">

                                    <a href="{{ route('customer.rentals.show', $r->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>

                                    @if(in_array($r->status, ['waiting_payment','paid']))
                                        <form method="POST"
                                            action="{{ route('customer.rentals.cancel', $r->id) }}"
                                            onsubmit="return confirm('Batalkan penyewaan ini?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger rounded-pill">
                                                ❌ Batalkan
                                            </button>
                                        </form>
                                    @endif

                                </td>


                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                @endif

            </div>
        </div>

    </div>
</section>
@endsection
