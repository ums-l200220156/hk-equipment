document.addEventListener('DOMContentLoaded', function() {
    const uploadBox = document.getElementById('upload-box');
    const input = document.getElementById('image-input');
    const preview = document.getElementById('preview-img');
    const ui = document.querySelector('.upload-ui');
    const priceInput = document.querySelector('.price-field');

    // Mencegah karakter non-angka pada field harga
    priceInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    uploadBox.onclick = () => input.click();

    input.onchange = function() {
        const [file] = this.files;
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            preview.style.width = '100%';
            ui.classList.add('d-none');
            
            Swal.fire({
                icon: 'success',
                title: 'Foto Terpilih',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500
            });
        }
    };

    document.getElementById('editForm').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Data unit akan diperbarui secara permanen.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff9800',
            cancelButtonColor: '#1a202c',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal',
            reverseButtons: true   //false jika ingin dikiri aksi utamanya dan batal dikanan
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    };
});