@extends('layouts.base')

@section('title','Bukti Bayar Overtime')

@push('styles')
{{-- Menggunakan file CSS baru khusus overtime --}}
<link rel="stylesheet" href="{{ asset('assets/css/customer/payment/overtime_transfer.css') }}">
@endpush

@section('content')
<section class="transfer-section">
    <div class="container">
        <div class="transfer-wrapper">
            
            <div class="info-side">
                <div class="badge-status-ot">⚡ Overtime Payment</div>
                <h2 class="title-display">Selesaikan Lembur</h2>
                <p class="subtitle">Segera upload bukti transfer biaya lembur agar status unit Anda diperbarui oleh Admin.</p>

                {{-- STYLE HARGA YANG DITAMPILKAN LEBIH JELAS --}}
                <div class="ot-price-card">
                    <span class="ot-label">TOTAL BIAYA LEMBUR</span>
                    <div class="ot-amount">Rp {{ number_format($overtime->price, 0, ',', '.') }}</div>
                    <div class="ot-decoration"></div>
                </div>

                <div class="bank-card-modern">
                    <div class="bank-chip"></div>
                    <div class="bank-content">
                        <div class="bank-brand">
                            <i class="bi bi-bank"></i>
                            <span>Bank BNI</span>
                        </div>
                        <div class="account-number" id="accountNumOT">1860846230</div>
                        <div class="account-holder">a.n Karuniawan Sulistya Nugroho</div>
                    </div>
                    <button class="btn-copy-ot" onclick="copyAccountOT()">
                        <i class="bi bi-content-copy"></i> Salin Nomor
                    </button>
                </div>

                <div class="payment-steps">
                    <div class="step-item">
                        <span class="step-num">1</span>
                        <p>Transfer nominal lembur ke rekening di atas.</p>
                    </div>
                    <div class="step-item">
                        <span class="step-num">2</span>
                        <p>Lampirkan bukti transfer di form konfirmasi.</p>
                    </div>
                </div>
            </div>

            <div class="form-side">
                <div class="upload-box-container">
                    <h3>Konfirmasi Bukti</h3>
                    <form method="POST" action="{{ route('payment.overtime.upload', $overtime->id) }}" enctype="multipart/form-data" id="formTransferOvertime">
                        @csrf
                        <div class="upload-area" id="uploadArea">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <p>Klik atau drop file bukti transfer lembur</p>
                            <input type="file" name="proof" id="fileInput" hidden>
                            <div id="fileName" class="file-name-display"></div>
                        </div>

                        <div class="security-info">
                            <i class="bi bi-shield-check"></i>
                            Sistem pembayaran HK Equipment terverifikasi.
                        </div>

                        <button type="submit" class="btn-gradient-submit-ot">
                            <span>Kirim Bukti Overtime</span>
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Menggunakan file JS baru khusus overtime --}}
<script src="{{ asset('assets/js/customer/payment/overtime_transfer.js') }}"></script>
@endpush