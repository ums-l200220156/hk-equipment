/**
 * Fungsi Toggle Lihat/Sembunyikan Password
 */
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
}

/**
 * Logika Preview Image & Efek Input
 */
function previewImage(input) {
    const preview = document.getElementById('profilePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="animate__animated animate__zoomIn">`;
            preview.style.borderColor = '#f59e0b';
            preview.style.background = '#fff';
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Interaksi Fokus Input
document.querySelectorAll('.hk-input').forEach(input => {
    input.addEventListener('focus', () => {
        const icon = input.parentElement.querySelector('i:first-child');
        if(icon) icon.style.color = '#f59e0b';
    });
    input.addEventListener('blur', () => {
        const icon = input.parentElement.querySelector('i:first-child');
        if(icon) icon.style.color = '#94a3b8';
    });
});