/**
 * HK RESET PASSWORD JS - INDEPENDENT
 */

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.hk-input');

    // 1. Efek Focus pada Icon dan Border
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            const icon = input.parentElement.querySelector('i:first-child');
            if (icon) icon.style.color = '#f59e0b';
        });

        input.addEventListener('blur', () => {
            const icon = input.parentElement.querySelector('i:first-child');
            if (icon) icon.style.color = '#64748b';
        });
    });

    // 2. Simulasi Loading saat Submit
    const form = document.querySelector('form');
    const btn = document.querySelector('.hk-btn-submit');

    if (form) {
        form.addEventListener('submit', function() {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> MEMPROSES...';
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.8';
        });
    }
});

/**
 * Fungsi Toggle Password
 * @param {string} inputId - ID dari input password
 * @param {string} iconId - ID dari icon yang diklik
 */
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
        toggleIcon.style.color = '#f59e0b';
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
        toggleIcon.style.color = '#64748b';
    }
}