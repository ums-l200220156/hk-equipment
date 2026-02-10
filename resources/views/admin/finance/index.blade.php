@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="bi bi-cash-stack me-2 text-success"></i>LAPORAN KEUANGAN</h3>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTransaksi">
            <i class="bi bi-plus-lg me-1"></i> Catat Pengeluaran
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white border-start border-primary border-4">
                <small class="text-muted fw-bold">TOTAL PEMASUKAN</small>
                <h4 class="fw-800 text-primary">Rp {{ number_format($totalIn, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white border-start border-danger border-4">
                <small class="text-muted fw-bold">TOTAL PENGELUARAN</small>
                <h4 class="fw-800 text-danger">Rp {{ number_format($totalOut, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-dark text-white">
                <small class="opacity-75 fw-bold">SALDO KAS (NETO)</small>
                <h4 class="fw-800">Rp {{ number_format($totalIn - $totalOut, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold m-0">Riwayat Arus Kas</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">TANGGAL</th>
                        <th>KETERANGAN</th>
                        <th>TIPE</th>
                        <th>NOMINAL</th>
                        <th class="text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($finances as $f)
                    <tr>
                        <td class="ps-4">{{ $f->created_at->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $f->description }}</td>
                        <td>
                            <span class="badge {{ $f->type == 'in' ? 'bg-success' : 'bg-danger' }}">
                                {{ $f->type == 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="fw-bold {{ $f->type == 'in' ? 'text-success' : 'text-danger' }}">
                            {{ $f->type == 'in' ? '+' : '-' }} Rp {{ number_format($f->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.finance.destroy', $f->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link text-muted btn-sm" onclick="return confirm('Hapus data ini?')">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTransaksi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.finance.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header border-0 bg-light">
                <h5 class="fw-bold m-0">Catat Transaksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="fw-bold small">Keterangan</label>
                    <input type="text" name="description" class="form-control" placeholder="Contoh: Ganti Oli Excavator PC200" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small">Tipe Kas</label>
                    <select name="type" class="form-select" required>
                        <option value="out">Pengeluaran (Out)</option>
                        <option value="in">Pemasukan (In)</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="fw-bold small">Nominal (Rp)</label>
                    <input type="number" name="amount" class="form-control" placeholder="0" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-success w-100 fw-bold py-2">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>
@endsection