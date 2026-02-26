<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - HK Equipment</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('assets/css/auth/reset-password.css') }}">
</head>
<body>

<div class="hk-reset-page">
    <div class="hk-grid-overlay"></div>
    <div class="hk-glow-sphere"></div>

    <div class="hk-reset-card animate__animated animate__fadeInUp">
        <div class="text-center mb-4">
            <div class="hk-logo-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <h2 class="hk-title">PASSWORD <span class="text-warning">BARU</span></h2>
            <p class="hk-subtitle">Silakan atur kata sandi baru untuk keamanan akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            {{-- Hidden context data --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

            {{-- Password Baru --}}
            <div class="mb-3">
                <label class="hk-label">PASSWORD BARU</label>
                <div class="hk-input-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" id="password" class="hk-input" placeholder="********" required autofocus>
                    <button type="button" class="hk-toggle-password" onclick="togglePassword('password', 'icon-pass')">
                        <i class="bi bi-eye-slash" id="icon-pass"></i>
                    </button>
                </div>
                @error('password') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label class="hk-label">KONFIRMASI PASSWORD</label>
                <div class="hk-input-group">
                    <i class="bi bi-check-circle-fill"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="hk-input" placeholder="********" required>
                    <button type="button" class="hk-toggle-password" onclick="togglePassword('password_confirmation', 'icon-conf')">
                        <i class="bi bi-eye-slash" id="icon-conf"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="hk-btn-submit">
                <span>UPDATE PASSWORD</span>
                <i class="bi bi-arrow-right-short"></i>
            </button>
        </form>
    </div>
</div>

<script src="{{ asset('assets/js/auth/reset-password.js') }}"></script>
</body>
</html>