@extends('layouts.admin')

@section('title', 'Tambah Pelanggan Baru')

@section('content')
<div class="container py-4">

    <header class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="fw-bolder text-dark">
            <i class="bi bi-person-plus-fill text-primary-custom me-2"></i> Tambah Pelanggan Baru
        </h2>
        <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </header>

    {{-- Alert untuk Validasi Errors --}}
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h4 class="alert-heading fs-5"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan!</h4>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="card custom-card shadow-lg p-3">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.customer.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            <small class="form-text text-muted">Akan digunakan sebagai username Login.</small>
                        </div>

                        {{-- Password + icon show/hide --}}
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                    <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="misal: 0812...">
                        </div>

                    </div>

                    <div class="col-md-6">


                        {{-- Address --}}
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                            <small class="form-text text-muted">Alamat pengiriman/tagihan utama.</small>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin (Internal)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>

                    </div>
                </div>
                
                <div class="border-top pt-3 mt-3 text-end">
                    <button type="submit" class="btn btn-primary-custom fw-bold shadow-sm">
                        <i class="bi bi-save-fill me-1"></i> Simpan Pelanggan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Script Toggle Password --}}
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput  = document.getElementById('password');
    const toggleIcon     = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type');

        if (type === 'password') {
            passwordInput.setAttribute('type', 'text');
            toggleIcon.classList.remove('bi-eye-fill');
            toggleIcon.classList.add('bi-eye-slash-fill');
        } else {
            passwordInput.setAttribute('type', 'password');
            toggleIcon.classList.remove('bi-eye-slash-fill');
            toggleIcon.classList.add('bi-eye-fill');
        }
    });
</script>

@endsection
