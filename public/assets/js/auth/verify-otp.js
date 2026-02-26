/**
 * HK OTP VERIFICATION JS
 */

document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.querySelector('.hk-input-otp');

    // Pastikan hanya angka yang bisa diinput
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Tambahkan efek sedikit glow saat diisi
    otpInput.addEventListener('keyup', function() {
        if(this.value.length === 6) {
            this.style.borderColor = '#25D366';
        } else {
            this.style.borderColor = 'rgba(255, 255, 255, 0.05)';
        }
    });
});