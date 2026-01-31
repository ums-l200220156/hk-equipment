@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Kelola Transaksi Penyewaan</h3>

    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="waiting_payment" {{ request('status')=='waiting_payment'?'selected':'' }}>Menunggu Pembayaran</option>
            <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Sudah Dibayar</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option>
            <option value="on_progress" {{ request('status')=='on_progress'?'selected':'' }}>Berjalan</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Selesai</option>
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Dibatalkan</option>
        </select>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Alat</th>
                    <th>Tanggal</th>
                    <th>Durasi</th>
                    <th>Lokasi</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rentals as $r)
                <tr>
                    <td class="fw-bold">#{{ $r->id }}</td>

                    <td>
                        <strong>{{ $r->user->name }}</strong><br>
                        <small class="text-muted">{{ $r->user->email }}</small>
                    </td>

                    <td>{{ $r->equipment->name }}</td>

                    <td>
                        {{ date('d M Y', strtotime($r->rent_date)) }}<br>
                        <small>Jam {{ $r->start_time }}</small>
                    </td>

                    <td>{{ $r->duration_hours }} Jam</td>

                    <td>{{ $r->location }}</td>

                    <td class="fw-bold">
                        Rp {{ number_format($r->total_price,0,',','.') }}
                    </td>

                    <td>
                        @php
                            $colors = [
                                'waiting_payment' => 'secondary',
                                'paid' => 'success',
                                'approved' => 'info',
                                'on_progress' => 'primary',
                                'completed' => 'dark',
                                'cancelled' => 'danger'
                            ];
                        @endphp

                        <span class="badge bg-{{ $colors[$r->status] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_',' ', $r->status)) }}
                        </span>
                    </td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.rentals.status', $r->id) }}"
                              class="status-form">
                            @csrf

                            <select name="status"
                                    class="form-select form-select-sm status-select"
                                    data-current="{{ $r->status }}">

                                <option value="waiting_payment" {{ $r->status=='waiting_payment'?'selected':'' }}>
                                    Menunggu Pembayaran
                                </option>

                                <option value="paid" {{ $r->status=='paid'?'selected':'' }}>
                                    Sudah Dibayar
                                </option>

                                <option value="approved" {{ $r->status=='approved'?'selected':'' }}>
                                    Disetujui
                                </option>

                                <option value="on_progress" {{ $r->status=='on_progress'?'selected':'' }}>
                                    Berjalan
                                </option>

                                <option value="completed" {{ $r->status=='completed'?'selected':'' }}>
                                    Selesai
                                </option>

                                <option value="cancelled" {{ $r->status=='cancelled'?'selected':'' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        Belum ada transaksi penyewaan.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function () {
        const form = this.closest('form');
        const oldValue = this.dataset.current;

        Swal.fire({
            title: 'Konfirmasi Perubahan Status',
            text: 'Apakah Anda yakin ingin mengubah status transaksi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else {
                this.value = oldValue;
            }
        });
    });
});
</script>
@endpush
