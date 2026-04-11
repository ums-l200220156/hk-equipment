

document.addEventListener('DOMContentLoaded', function() {
    
    // Logika Show/Hide Password
    const toggles = document.querySelectorAll('.password-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            
            if (input.type === "password") {
                input.type = "text";
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            } else {
                input.type = "password";
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            }
        });
    });

});

// Fungsi Global untuk SweetAlert Success
function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        background: '#1e1e26',
        color: '#fff',
        confirmButtonColor: '#ff416c',
        timer: 3000
    });
}