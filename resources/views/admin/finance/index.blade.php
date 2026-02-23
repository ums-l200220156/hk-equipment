@extends('layouts.admin')
@section('title', 'Financial Analytics - HK Equipment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/finance/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="hk-finance-wrapper">
    {{-- 01. LUXURY HEADER --}}
    <div class="hk-glass-card mb-4 animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="hk-badge-finance"><i class="bi bi-safe2"></i> FINANCIAL COMMAND CENTER</div>
                <h2 class="hk-page-title mt-2">Arus Kas <span class="text-danger">&</span> Profitabilitas</h2>
                <p class="text-muted mb-0">Monitoring pemasukan otomatis dan kontrol pengeluaran operasional secara presisi.</p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div class="hk-filter-nav shadow-sm">
                    <a href="?period=weekly" class="nav-link-custom {{ $period == 'weekly' ? 'active' : '' }}">Mingguan</a>
                    <a href="?period=monthly" class="nav-link-custom {{ $period == 'monthly' ? 'active' : '' }}">Bulanan</a>
                    <a href="?period=yearly" class="nav-link-custom {{ $period == 'yearly' ? 'active' : '' }}">Tahunan</a>
                </div>
            </div>
        </div>
    </div>

    {{-- 02. ANALYTICS CARDS (Symmetrical Grid) --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-4 animate__animated animate__fadeInLeft">
            <div class="finance-card income">
                <div class="card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="card-content">
                    <small class="label">TOTAL PEMASUKAN</small>
                    <h3 class="amount">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                    <div class="mini-stats mt-3">
                        <div class="d-flex justify-content-between small opacity-75">
                            <span>Unit Rental</span>
                            <span>Rp {{ number_format($rentalIncome, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small opacity-75 mt-1">
                            <span>Overtime Log</span>
                            <span>Rp {{ number_format($overtimeIncome, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 animate__animated animate__fadeInUp">
            <div class="finance-card expense">
                <div class="card-icon"><i class="bi bi-cart-dash"></i></div>
                <div class="card-content">
                    <small class="label">TOTAL PENGELUARAN</small>
                    <h3 class="amount">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                    <div class="progress-container mt-4">
                        @php $ratio = $totalIncome > 0 ? ($totalExpense / $totalIncome) * 100 : 0; @endphp
                        <div class="progress" style="height: 8px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-white" style="width: {{ $ratio }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small">
                            <span>Rasio Biaya</span>
                            <span>{{ round($ratio, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 animate__animated animate__fadeInRight">
            <div class="finance-card balance">
                <div class="card-icon"><i class="bi bi-bank"></i></div>
                <div class="card-content">
                    <small class="label">SALDO BERSIH (PROFIT)</small>
                    <h3 class="amount">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                    <div class="status-indicator mt-4">
                        <span class="badge bg-light text-dark py-2 px-3 rounded-pill fw-bold">
                            <i class="bi bi-shield-check text-success me-1"></i> VERIFIED BY SYSTEM
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 03. TRANSACTION LOG --}}
    <div class="hk-main-card shadow-lg animate__animated animate__fadeInUp">
        <div class="card-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="bi bi-list-columns-reverse me-2"></i> RINCIAN PENGELUARAN</h5>
                <button class="btn btn-navy-modern shadow-sm" data-bs-toggle="modal" data-bs-target="#modalExpense">
                    <i class="bi bi-plus-circle-fill me-2"></i> INPUT PENGELUARAN
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle hk-table-modern">
                <thead>
                    <tr>
                        <th class="ps-4">TANGGAL</th>
                        <th>KATEGORI</th>
                        <th>KETERANGAN</th>
                        <th class="text-end">NOMINAL</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $row)
                    <tr>
                        <td class="ps-4" data-label="Tanggal">
                            <div class="fw-bold text-navy">{{ $row->transaction_date->format('d/m/Y') }}</div>
                        </td>
                        <td data-label="Kategori"><span class="badge-cat-modern">{{ strtoupper($row->category) }}</span></td>
                        <td data-label="Deskripsi"><p class="mb-0 text-muted small" style="max-width: 300px;">{{ $row->description ?? 'Tanpa deskripsi' }}</p></td>
                        <td class="text-end fw-extrabold text-danger" data-label="Nominal">Rp {{ number_format($row->amount, 0, ',', '.') }}</td>
                        <td class="text-center" data-label="Aksi">
                            <form action="{{ route('admin.finance.destroy', $row->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-delete-luxury" onclick="confirmDelete(this.form)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data pengeluaran ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL INPUT (MANUAL CATEGORY) --}}
<div class="modal fade" id="modalExpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content hk-modal-style">
            <form action="{{ route('admin.finance.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="fw-bold m-0 text-navy">ENTRY PENGELUARAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">KATEGORI PENGELUARAN</label>
                        <input type="text" name="category" class="form-control-custom" placeholder="Misal: Perbaikan Mesin, BBM, Gaji" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <label class="form-label-custom">NOMINAL (RP)</label>
                            <input type="number" name="amount" class="form-control-custom" placeholder="0" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label-custom">TANGGAL</label>
                            <input type="date" name="transaction_date" class="form-control-custom" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label-custom">DESKRIPSI LENGKAP</label>
                        <textarea name="description" class="form-control-custom" rows="3" placeholder="Tulis rincian pengeluaran di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-navy-modern w-100 py-3">KONFIRMASI PENYIMPANAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/finance/index.js') }}"></script>
    @if(session('swal_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Transaksi Berhasil',
                text: "{{ session('swal_success') }}",
                timer: 2500,
                showConfirmButton: false,
                borderRadius: '15px'
            });
        });
    </script>
    @endif
@endpush