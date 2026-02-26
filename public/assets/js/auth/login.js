/**
 * HK LOGIN SYSTEM 
 */

// 1. Toggle Lihat Password
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

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

// 2. Efek Input Focus
document.querySelectorAll('.hk-input').forEach(input => {
    input.addEventListener('focus', () => {
        const icon = input.parentElement.querySelector('i:first-child');
        if (icon) icon.style.color = '#f59e0b';
    });

    input.addEventListener('blur', () => {
        const icon = input.parentElement.querySelector('i:first-child');
        if (icon) icon.style.color = '#64748b';
    });
});