@extends('layouts.admin') 

@push('styles')
    {{-- CSS Khusus Halaman Profil --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin/profile/edit.css') }}">
@endpush

@section('content')
<div class="profile-edit-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-edit-card animate__animated animate__fadeInUp">
                    
                    {{-- HEADER --}}
                    <div class="edit-header">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('admin.dashboard.index') }}" class="btn-back">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <h3 class="fw-bold text-white mb-0">Pengaturan Profil Admin</h3>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- SECTION PERSONAL --}}
                            <div class="section-divider mb-4">
                                <span class="text-danger fw-bold small text-uppercase tracking-wider">Informasi Akun</span>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label>Nama Lengkap</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-person"></i>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label>Alamat Email</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group-modern">
                                        <label>Nomor Telepon (WhatsApp)</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-whatsapp"></i>
                                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="62812xxx">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION PASSWORD --}}
                            <div class="section-divider mb-4">
                                <span class="text-warning fw-bold small text-uppercase tracking-wider">Keamanan</span>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label>Password Baru</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-key"></i>
                                            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ganti">
                                            <i class="bi bi-eye password-toggle" data-target="password"></i>
                                        </div>
                                        @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label>Konfirmasi Password</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-shield-check"></i>
                                            <input type="password" name="password_confirmation" id="password_confirmation">
                                            <i class="bi bi-eye password-toggle" data-target="password_confirmation"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit" class="btn-save-modern shadow">
                                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- SweetAlert & JS Khusus --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/admin/profile/edit.js') }}"></script>
    
    @if(session('success'))
    <script>
        showSuccessAlert("{{ session('success') }}");
    </script>
    @endif
@endpush