/**
 * HK RECOVERY INDEPENDENT JS
 */

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.hk-input');

    // 1. Efek Focus Input
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            const icon = input.parentElement.querySelector('i');
            if (icon) icon.style.color = '#f59e0b';
        });

        input.addEventListener('blur', () => {
            const icon = input.parentElement.querySelector('i');
            if (icon) icon.style.color = '#64748b';
        });
    });
});

// 2. Switch Method Logic
function switchMethod(method) {
    // Update Button UI
    document.querySelectorAll('.method-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.method-btn[data-method="${method}"]`).classList.add('active');

    // Update Form Visibility
    const forms = document.querySelectorAll('.method-form');
    forms.forEach(form => {
        form.style.display = 'none';
    });

    const activeForm = document.getElementById(`form-${method}`);
    activeForm.style.display = 'block';
    
    // Reset warna icon jika switch form
    document.querySelectorAll('.hk-input-group i').forEach(icon => {
        icon.style.color = '#64748b';
    });
}