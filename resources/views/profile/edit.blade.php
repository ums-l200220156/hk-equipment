@extends('layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/customer/profile/edit.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
@endpush

@section('content')
<div class="profile-edit-container py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-edit-card" data-aos="zoom-in">
                    
                    <div class="edit-header">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('customer.profile') }}" class="btn-back">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <h3 class="fw-bold text-white mb-0">Pengaturan Akun</h3>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        {{-- FORM UPDATE PROFIL & FOTO --}}
                        <form id="formUpdateProfile" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="text-center mb-5">
                                <div class="profile-upload-wrapper">
                                    <label for="imageUpload" class="profile-preview-container shadow-lg">
                                        @if(Auth::user()->image)
                                            <img src="{{ Auth::user()->image 
                                                ? asset('uploads/users/'.Auth::user()->image) 
                                                : asset('assets/images/default-avatar.png') }}" id="imagePreview" alt="Avatar">
                                        @else
                                            <div id="initialPreview" class="initial-circle-edit">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                            <img src="" id="imagePreview" style="display:none;" alt="Avatar">
                                        @endif
                                        <div class="avatar-overlay">
                                            <i class="bi bi-camera-fill"></i>
                                            <span>Ganti Foto</span>
                                        </div>
                                    </label>
                                    <input type="file" name="image" id="imageUpload" accept="image/*" class="d-none">
                                </div>
                                <p class="text-white-50 small mt-3">Klik pada foto untuk memperbarui profil visual Anda</p>
                                @error('image') <span class="error-msg d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="section-divider mb-4">
                                <span class="text-danger fw-bold small text-uppercase tracking-wider">Informasi Personal</span>
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
                                        <label>Email Address</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label>Nomor WhatsApp</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-whatsapp"></i>
                                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="0812xxx">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="input-group-modern">
                                        <label>Alamat Pengiriman</label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-geo-alt"></i>
                                            <textarea name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-5">
                                <button type="submit" class="btn-save-modern shadow">
                                    <i class="bi bi-shield-check me-2"></i> Update Profil
                                </button>
                            </div>
                        </form>

                        <hr class="border-secondary opacity-25 mb-5">

                        {{-- FORM UPDATE PASSWORD --}}
                        <form id="formUpdatePassword" method="post" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('put')

                            <div class="section-divider mb-4">
                                <span class="text-warning fw-bold small text-uppercase tracking-wider">Keamanan & Password</span>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="input-group-modern">
                                        <label>Password Saat Ini</label>
                                        <div class="input-wrapper password-wrapper">
                                            <i class="bi bi-lock"></i>
                                            <input type="password" name="current_password" required>
                                            <i class="bi bi-eye-slash toggle-password"></i>
                                        </div>
                                        @error('current_password', 'updatePassword') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group-modern">
                                        <label>Password Baru</label>
                                        <div class="input-wrapper password-wrapper">
                                            <i class="bi bi-key"></i>
                                            <input type="password" name="password" required>
                                            <i class="bi bi-eye-slash toggle-password"></i>
                                        </div>
                                        @error('password', 'updatePassword') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group-modern">
                                        <label>Konfirmasi Password</label>
                                        <div class="input-wrapper password-wrapper">
                                            <i class="bi bi-check-all"></i>
                                            <input type="password" name="password_confirmation" required>
                                            <i class="bi bi-eye-slash toggle-password"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn-password-modern shadow">
                                    <i class="bi bi-arrow-repeat me-2"></i> Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL CROPPER -->
<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-0 describing shadow-lg" style="border-radius:20px;">
            
            <div class="modal-body p-3">
                <div class="text-center mb-3 text-white fw-bold">
                    Sesuaikan Foto Profil
                </div>

                <div style="max-height: 400px;">
                    <img id="cropImage" style="width:100%;">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" id="cropBtn" class="btn btn-danger">
                        Crop & Gunakan
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="{{ asset('assets/js/customer/profile/edit.js') }}?v={{ time() }}"></script>
    
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            background: '#1e1e26',
            color: '#fff',
            confirmButtonColor: '#ff416c'
        });
    </script>
    @endif

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            background: '#1e1e26',
            color: '#fff',
            confirmButtonColor: '#ff416c'
        });
    </script>
    @endif
@endpush