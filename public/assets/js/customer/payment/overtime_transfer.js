document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileName');
    const formOvertime = document.getElementById('formTransferOvertime');

    if (uploadArea && fileInput) {
        
        // 1. KLIK UNTUK UPLOAD
        uploadArea.addEventListener('click', () => fileInput.click());

        // 2. DRAG & DROP LOGIC
        // Mencegah browser membuka file secara otomatis
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        // Efek visual saat file ditarik di atas area
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('active');
            }, false);
        });

        // Menghilangkan efek visual saat file batal di-drop
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                if (!fileInput.files.length) {
                    uploadArea.classList.remove('active');
                }
            }, false);
        });

        // MENANGKAP FILE SAAT DI-DROP
        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files; // Memasukkan file ke input
                handleFileOT(files[0]);    
            }
        });

        // 3. PREVIEW SAAT INPUT BERUBAH
        fileInput.addEventListener('change', function() {
            handleFileOT(this.files[0]);
        });

        function handleFileOT(file) {
            if (file) {
                fileNameDisplay.innerText = "Terpilih: " + file.name;
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

    // 4. VALIDASI SWEETALERT SEBELUM SUBMIT
    if (formOvertime) {
        formOvertime.addEventListener('submit', function(e) {
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bukti Belum Diunggah!',
                    text: 'Harap lampirkan foto bukti transfer lembur Anda terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Oke, Saya Paham'
                });
            }
        });
    }
});

// 5. FITUR COPY NOMOR REKENING
function copyAccountOT() {
    const accountNum = "1860846230";
    navigator.clipboard.writeText(accountNum).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil disalin!',
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: 'top-end'
        });
    });
}