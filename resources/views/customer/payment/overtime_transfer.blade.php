@extends('layouts.base')

@section('title','Bukti Bayar Overtime')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/customer/payment/transfer.css') }}">
@endpush

@section('content')
<section class="transfer-section">
    <div class="container">
        <div class="transfer-wrapper">
            <div class="info-side">
                <div class="badge-status">Overtime Payment</div>
                <h2 class="title-display">Upload Bukti Bayar</h2>
                <p class="subtitle">Nominal Overtime: <strong>Rp {{ number_format($overtime->price, 0, ',', '.') }}</strong></p>

                <div class="bank-card-modern">
                    <div class="bank-content">
                        <div class="bank-brand"><i class="bi bi-bank"></i> <span>Bank BNI</span></div>
                        <div class="account-number">1860846230</div>
                        <div class="account-holder">a.n Karuniawan Sulistya Nugroho</div>
                    </div>
                </div>
            </div>

            <div class="form-side">
                <div class="upload-box-container">
                    <h3>Konfirmasi Bukti</h3>
                    {{-- Menambahkan ID form untuk validasi JS --}}
                    <form method="POST" action="{{ route('payment.overtime.upload', $overtime->id) }}" enctype="multipart/form-data" id="formTransferOvertime">
                        @csrf
                        <div class="upload-area" id="uploadArea">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <p>Klik/Drop foto bukti transfer lembur</p>
                            {{-- Atribut required dilepas agar ditangani oleh SweetAlert --}}
                            <input type="file" name="proof" id="fileInput" hidden>
                            <div id="fileName" class="file-name-display"></div>
                        </div>

                        <button type="submit" class="btn-gradient-submit">
                            <span>Kirim Bukti Overtime</span> <i class="bi bi-send-fill"></i>
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
<script src="{{ asset('assets/js/customer/payment/transfer.js') }}"></script>
@endpush