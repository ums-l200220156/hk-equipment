<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-hk.png') }}">
    <title>Lupa Password - HK Equipment</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    
    <link rel="stylesheet" href="{{ asset('assets/css/auth/forgot-password.css') }}">
</head>
<body>

<div class="hk-recovery-page">
    <div class="hk-grid-overlay"></div>
    <div class="hk-glow-sphere"></div>
    
    <div class="hk-recovery-card">
        <div class="text-center">
            <div class="hk-logo-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <h2 class="hk-title">PEMULIHAN <span class="text-warning">AKUN</span></h2>
            <p class="hk-subtitle">Silakan pilih metode verifikasi mandiri.</p>
        </div>

        {{-- Selector --}}
        <div class="method-selector">
            <button type="button" class="method-btn active" data-method="email" onclick="switchMethod('email')">
                <i class="bi bi-envelope-at"></i>
                <span>Email</span>
            </button>
            <button type="button" class="method-btn" data-method="wa" onclick="switchMethod('wa')">
                <i class="bi bi-whatsapp"></i>
                <span>WhatsApp</span>
            </button>
        </div>

        {{-- Form Email --}}
        <form method="POST" action="{{ route('password.email') }}" id="form-email" class="method-form">
            @csrf
            <div class="mb-3">
                <label class="hk-label">Alamat Email</label>
                <div class="hk-input-group">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" name="email" class="hk-input" placeholder="admin@hkequipment.com" required>
                </div>
            </div>
            <button type="submit" class="hk-btn-submit btn-email">KIRIM LINK RESET</button>
        </form>

        {{-- Form WhatsApp --}}
        <form method="POST" action="{{ route('password.wa') }}" id="form-wa" class="method-form" style="display: none;">
            @csrf
            <div class="mb-3">
                <label class="hk-label">Nomor WhatsApp</label>
                <div class="hk-input-group">
                    <i class="bi bi-whatsapp"></i>
                    <input type="text" name="phone" class="hk-input" placeholder="08xxxxxxxxxx" required>
                </div>
                <p style="color: #94a3b8; font-size: 0.75rem; margin-top: 5px;">Kami akan mengirimkan kode OTP untuk reset password.</p>
            </div>
            <button type="submit" class="hk-btn-submit btn-wa">KIRIM KODE OTP</button>
        </form>

        <a href="{{ route('login') }}" class="hk-back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Login
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/auth/forgot-password.js') }}"></script>

<script>
    // SweetAlert Handler
    @if(session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('status') }}",
            background: '#1e293b',
            color: '#fff',
            confirmButtonColor: '#f59e0b'
        });
    @endif
</script>

</body>
</html>