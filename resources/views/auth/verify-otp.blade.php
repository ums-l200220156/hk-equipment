<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-hk.png') }}">
    <title>Verifikasi OTP - HK Equipment</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Aset Mandiri Khusus OTP --}}
    <link rel="stylesheet" href="{{ asset('assets/css/auth/verify-otp.css') }}">
</head>
<body>

<div class="hk-recovery-page">
    <div class="hk-grid-overlay"></div>
    <div class="hk-glow-sphere"></div>
    
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="hk-recovery-card animate__animated animate__fadeInUp">
                    
                    <div class="text-center">
                        <div class="hk-logo-icon"><i class="bi bi-patch-check-fill"></i></div>
                        <h2 class="hk-title">VERIFIKASI <span class="text-warning">OTP</span></h2>
                        <p class="hk-subtitle">Masukkan 6 digit kode yang dikirim ke nomor WhatsApp Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('password.otp.verify') }}">
                        @csrf
                        {{-- Hidden input phone context --}}
                        <input type="hidden" name="phone" value="{{ request('phone') }}">
                        
                        <div class="hk-otp-container">
                            <input type="text" name="otp" 
                                class="hk-input-otp" 
                                maxlength="6" 
                                placeholder="000000" 
                                required autofocus autocomplete="off">
                            
                            @if($errors->has('otp'))
                                <small class="text-danger d-block text-center mt-2">{{ $errors->first('otp') }}</small>
                            @endif
                        </div>

                        <button type="submit" class="hk-btn-verify">
                            VERIFIKASI KODE
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="hk-subtitle" style="font-size: 0.75rem;">
                            Tidak menerima kode? 
                            <a href="{{ route('password.request') }}" class="hk-link-warning">Kirim ulang</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/auth/verify-otp.js') }}"></script>

<script>
    @if(session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('status') }}",
            background: '#1e293b',
            color: '#fff',
            confirmButtonColor: '#25D366'
        });
    @endif
</script>

</body>
</html>