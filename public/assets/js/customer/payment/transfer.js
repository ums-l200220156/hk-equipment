document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileName');

    if (uploadArea && fileInput) {
        
        // --- FITUR KLIK UNTUK UPLOAD ---
        uploadArea.addEventListener('click', () => fileInput.click());

        // --- FITUR DRAG & DROP ---
        // Mencegah browser membuka gambar otomatis saat file diseret masuk
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        // Efek visual saat file sedang diseret di atas box
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('active');
            }, false);
        });

        // Hapus efek visual jika file batal diseret (keluar dari box)
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                if (!fileInput.files.length) uploadArea.classList.remove('active');
            }, false);
        });

        // Menangani saat file DILEPASKAN (Drop)
        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files; // Masukkan file drop ke input asli
                handleFile(files[0]);    // Jalankan logika preview
            }
        });

        // --- FITUR PREVIEW GAMBAR (LOGIKA ASLI ANDA) ---
        fileInput.addEventListener('change', function() {
            handleFile(this.files[0]);
        });

        function handleFile(file) {
            if (file) {
                fileNameDisplay.innerText = "File terpilih: " + file.name;
                uploadArea.classList.add('active');

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        let previewImg = document.getElementById('previewImg');
                        if (!previewImg) {
                            previewImg = document.createElement('img');
                            previewImg.id = 'previewImg';
                            previewImg.className = 'img-preview-result';
                            uploadArea.prepend(previewImg);
                        }
                        previewImg.src = e.target.result;
                        
                        const cloudIcon = uploadArea.querySelector('.bi-cloud-arrow-up-fill');
                        if (cloudIcon) cloudIcon.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    }
});

// --- FITUR COPY NOMOR REKENING ---
function copyToClipboard() {
    const accountNum = "1860846230";
    navigator.clipboard.writeText(accountNum).then(() => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil disalin!',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
        }
    });
}