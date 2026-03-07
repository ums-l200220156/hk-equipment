<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-hk.png') }}">
    <title>Register - HK Equipment</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('assets/css/auth/register.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
</head>
<body>

<div class="hk-auth-page">
    <div class="hk-bg-pattern"></div>
    <div class="hk-bg-glow"></div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-xl-10">
                
                {{-- Notifikasi Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4 animate__animated animate__shakeX" style="border-radius: 15px;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="hk-register-card animate__animated animate__fadeIn">
                    <div class="row g-0">
                        
                        {{-- Sisi Kiri: Branding --}}
                        <div class="col-lg-5 hk-auth-sidebar d-none d-lg-flex">
                            <div class="hk-sidebar-content">
                                <div class="hk-logo-area mb-4">
                                    <i class="bi bi-gear-wide-connected"></i>
                                    <span>HK SYSTEM</span>
                                </div>
                                <h1 class="hk-sidebar-title">Bangun Infrastruktur <span class="text-warning">Bersama Kami.</span></h1>
                                <p class="hk-sidebar-text">Daftarkan akun Anda hari ini untuk mendapatkan akses prioritas ke armada alat berat terbaik kami.</p>
                                
                                <div class="hk-feature-list mt-5">
                                    <div class="hk-feature-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Penyewaan Instan 24/7</span>
                                    </div>
                                    <div class="hk-feature-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Monitoring Unit Real-time</span>
                                    </div>
                                    <div class="hk-feature-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Tarif Transparan & Kompetitif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Form --}}
                        <div class="col-lg-7">
                            <div class="hk-form-area">
                                <div class="hk-form-header mb-4">
                                    <h2 class="hk-form-title">Registrasi Akun</h2>
                                    <p class="text-muted">Lengkapi data diri Anda untuk bergabung.</p>
                                </div>

                                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Upload Foto Profil (Seluruh area klik) --}}
                                    <div class="hk-upload-wrapper mb-4">
                                        <label for="image" class="hk-preview-container" style="cursor: pointer;">
                                            <div class="hk-preview-circle" id="profilePreview">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </div>
                                            <div class="hk-upload-badge">
                                                <i class="bi bi-camera-fill"></i>
                                            </div>
                                        </label>
                                        <input id="image" type="file" name="image" class="d-none" accept="image/*" onchange="previewImage(this)">
                                        <div class="hk-upload-info">
                                            <span class="d-block fw-bold">Foto Profil</span>
                                            <span class="small text-muted">Klik area foto untuk upload</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="hk-label">Nama Lengkap</label>
                                            <div class="hk-input-group">
                                                <i class="bi bi-person"></i>
                                                <input type="text" name="name" class="hk-input" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="hk-label">Email Aktif</label>
                                            <div class="hk-input-group">
                                                <i class="bi bi-envelope"></i>
                                                <input type="email" name="email" class="hk-input" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="hk-label">Nomor Telepon / WhatsApp</label>
                                        <div class="hk-input-group">
                                            <i class="bi bi-whatsapp"></i>
                                            <input type="text" name="phone" class="hk-input" value="{{ old('phone') }}" placeholder="0812XXXX" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="hk-label">Alamat Domisili</label>
                                        <div class="hk-input-group align-items-start">
                                            <i class="bi bi-geo-alt mt-2"></i>
                                            <textarea name="address" class="hk-input hk-textarea" rows="2" placeholder="Masukkan alamat lengkap..." required>{{ old('address') }}</textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="role" value="customer">

                                    <div class="row">
                                        {{-- Password dengan Toggle --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="hk-label">Password</label>
                                            <div class="hk-input-group position-relative">
                                                <i class="bi bi-lock"></i>
                                                <input type="password" name="password" id="password" class="hk-input" placeholder="********" minlength="8" required>
                                                
                                                <span class="hk-toggle-password" data-target="password">
                                                    <i class="bi bi-eye-slash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Konfirmasi dengan Toggle --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="hk-label">Konfirmasi</label>
                                            <div class="hk-input-group position-relative">
                                                <i class="bi bi-shield-check"></i>
                                                <input type="password" name="password_confirmation" id="password_confirm" class="hk-input" placeholder="********" minlength="8" required>
                                                
                                                <span class="hk-toggle-password" data-target="password_confirm">
                                                    <i class="bi bi-eye-slash"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="hk-btn-primary w-100">
                                            <span>MULAI BERGABUNG</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                        <p class="text-center mt-4 mb-0">
                                            Sudah memiliki akun? <a href="{{ route('login') }}" class="hk-link-warning">Login Sekarang</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL CROPPER -->
<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            
            <div class="modal-body p-3 text-center">
                <h6 class="fw-bold mb-3">Sesuaikan Foto Profil</h6>

                <div style="max-height: 400px;">
                    <img id="cropImage" style="width:100%;">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" id="cropBtn" class="btn btn-warning">
                        Crop & Gunakan
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/auth/register.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</body>
</html>