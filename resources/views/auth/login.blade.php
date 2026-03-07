<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-hk.png') }}">
    <title>Login - HK Equipment System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">
</head>
<body>

<div class="hk-login-page">
    {{-- Efek Background Partikel --}}
    <div class="hk-grid-overlay"></div>
    <div class="hk-glow-sphere"></div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="hk-login-card animate__animated animate__fadeInUp">
                    
                    {{-- Logo & Header --}}
                    <div class="text-center mb-5">
                        <div class="hk-logo-icon">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <h2 class="hk-title">HK <span class="text-warning">SYSTEM</span></h2>
                        <p class="hk-subtitle">Silakan masuk untuk mengelola armada Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="hkLoginForm">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="hk-label">ALAMAT EMAIL</label>
                            <div class="hk-input-group">
                                <i class="bi bi-envelope-fill"></i>
                                <input type="email" name="email" class="hk-input" 
                                    value="{{ old('email') }}" placeholder="admin@hkequipment.com" 
                                    required autofocus>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="hk-label">PASSWORD</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="hk-forgot-link">Lupa Password?</a>
                                @endif
                            </div>
                            <div class="hk-input-group">
                                <i class="bi bi-shield-lock-fill"></i>
                                <input type="password" name="password" id="password" class="hk-input" 
                                    placeholder="********" required>
                                <button type="button" class="hk-toggle-btn" onclick="togglePassword()">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="mb-4 d-flex align-items-center">
                            <label class="hk-remember">
                                <input type="checkbox" name="remember">
                                <span class="ms-2">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="hk-btn-login w-100">
                            <span>MASUK KE SISTEM</span>
                            <i class="bi bi-box-arrow-in-right"></i>
                        </button>

                        {{-- Register Link --}}
                        <div class="hk-register-footer">
                            <p>
                                Belum menjadi mitra? 
                                <a href="{{ route('register') }}" class="hk-link-warning">Daftar Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
                
                {{-- Footer Copyright --}}
                <p class="text-center mt-4 text-white-50 small">
                    &copy; 2026 HK Equipment - Heavy Machinery Management.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/auth/login.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. NOTIFIKASI BERHASIL (Contoh: Setelah Registrasi)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                background: '#1e293b',
                color: '#fff',
                confirmButtonColor: '#f59e0b',
                timer: 4000,
                timerProgressBar: true
            });
        @endif

        // 2. NOTIFIKASI GAGAL (Salah Email/Password)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: 'Email atau password yang Anda masukkan tidak sesuai.',
                background: '#1e293b',
                color: '#fff',
                confirmButtonColor: '#ef4444',
                timer: 5000,
                timerProgressBar: true
            });
        @endif

        // 3. NOTIFIKASI STATUS (Contoh: Setelah Reset Password atau Logout)
        @if(session('status'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('status') }}",
                background: '#1e293b',
                color: '#fff',
                confirmButtonColor: '#3b82f6',
                timer: 4000,
                timerProgressBar: true
            });
        @endif
    });
</script>

</body>
</html>