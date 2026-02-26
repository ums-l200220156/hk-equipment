document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('drop-area');
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('placeholder-content');

    // 1. Trigger klik input file saat area diklik
    uploadArea.addEventListener('click', () => imageInput.click());

    // 2. Fungsi Reusable untuk menangani Preview
    function handleFiles(files) {
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
                
                // Styling visual area saat foto berhasil dimuat
                uploadArea.style.padding = '10px';
                uploadArea.style.borderStyle = 'solid';
                uploadArea.style.borderColor = '#22c55e'; // Warna hijau tanda sukses
                uploadArea.style.backgroundColor = 'rgba(34, 197, 94, 0.02)';
            }
            reader.readAsDataURL(file);
        }
    }

    // 3. Handle saat pilih file lewat tombol/klik manual
    imageInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    // 4. Handle Drag & Drop Logic
    // Mencegah perilaku default browser (seperti membuka gambar di tab baru)
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    // Efek visual saat file diseret di atas area
    uploadArea.addEventListener('dragover', () => {
        uploadArea.style.borderColor = '#3b82f6';
        uploadArea.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
        uploadArea.style.transform = 'scale(1.01)';
        uploadArea.style.transition = 'all 0.2s ease';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#cbd5e1';
        uploadArea.style.backgroundColor = 'transparent';
        uploadArea.style.transform = 'scale(1)';
    });

    // Eksekusi utama saat file dilepas (DROP)
    uploadArea.addEventListener('drop', (e) => {
        uploadArea.style.borderColor = '#cbd5e1';
        uploadArea.style.transform = 'scale(1)';
        
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            // PENTING: Masukkan file drop ke dalam input HTML agar terbaca oleh PHP/Laravel
            imageInput.files = files;
            
            // Jalankan preview
            handleFiles(files);
        }
    });
});