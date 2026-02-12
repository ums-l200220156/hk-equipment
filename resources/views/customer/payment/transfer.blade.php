@extends('layouts.base')

@section('title','Konfirmasi Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/customer/payment/transfer.css') }}">
@endpush

@section('content')
<section class="transfer-section">
    <div class="container">
        <div class="transfer-wrapper">
            
            <div class="info-side">
                <div class="badge-status">Menunggu Pembayaran</div>
                <h2 class="title-display">Satu langkah lagi!</h2>
                <p class="subtitle">Selesaikan pembayaran Anda agar alat berat bisa segera kami persiapkan.</p>

                <div class="bank-card-modern">
                    <div class="bank-chip"></div>
                    <div class="bank-content">
                        <div class="bank-brand">
                            <i class="bi bi-bank"></i>
                            <span>Bank BNI</span>
                        </div>
                        <div class="account-number" id="accountNum">1860846230</div>
                        <div class="account-holder">a.n Karuniawan Sulistya Nugroho</div>
                    </div>
                    <button class="btn-copy" onclick="copyToClipboard()">
                        <i class="bi bi-content-copy"></i> Salin Nomor
                    </button>
                </div>

                <div class="payment-steps">
                    <div class="step-item">
                        <span class="step-num">1</span>
                        <p>Transfer sesuai nominal ke rekening di atas.</p>
                    </div>
                    <div class="step-item">
                        <span class="step-num">2</span>
                        <p>Foto atau screenshot bukti transfer Anda.</p>
                    </div>
                    <div class="step-item">
                        <span class="step-num">3</span>
                        <p>Upload bukti pada form di samping.</p>
                    </div>
                </div>
            </div>

            <div class="form-side">
                <div class="upload-box-container">
                    <h3>Konfirmasi Bukti</h3>
                    {{-- Menambahkan ID form untuk validasi JS --}}
                    <form method="POST" action="{{ route('payment.upload',$rental->id) }}" enctype="multipart/form-data" id="formTransferSewa">
                        @csrf
                        <div class="upload-area" id="uploadArea">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <p>Klik atau drop file bukti transfer di sini</p>
                            {{-- Atribut required dilepas agar ditangani oleh SweetAlert --}}
                            <input type="file" name="payment_proof" id="fileInput" hidden>
                            <div id="fileName" class="file-name-display"></div>
                        </div>

                        <div class="security-info">
                            <i class="bi bi-shield-check"></i>
                            Pembayaran Anda aman dan terenkripsi.
                        </div>

                        <button type="submit" class="btn-gradient-submit">
                            <span>Kirim Bukti Pembayaran</span>
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
{{-- SweetAlert2 CDN harus sudah ada di layout utama atau dipanggil di sini --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/customer/payment/transfer.js') }}"></script>
@endpush