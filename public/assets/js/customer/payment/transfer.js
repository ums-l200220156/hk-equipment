document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileName');
    
    // Selector untuk kedua jenis form (Sewa & Overtime)
    const formSewa = document.getElementById('formTransferSewa');
    const formOvertime = document.getElementById('formTransferOvertime');

    if (uploadArea && fileInput) {
        
        // --- FITUR KLIK UNTUK UPLOAD ---
        uploadArea.addEventListener('click', () => fileInput.click());

        // --- FITUR DRAG & DROP ---
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('active');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                if (!fileInput.files.length) uploadArea.classList.remove('active');
            }, false);
        });

        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files; 
                handleFile(files[0]);    
            }
        });

        // --- FITUR PREVIEW GAMBAR ---
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

    // --- VALIDASI SWEETALERT SEBELUM SUBMIT ---
    const validateSubmit = (e) => {
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault(); // Stop form submission
            Swal.fire({
                title: 'Bukti Belum Diunggah!',
                text: 'Harap lampirkan foto atau screenshot bukti transfer Anda terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b', // Warna Kuning/Orange sesuai tema
                confirmButtonText: 'Oke, Saya Paham',
                background: '#ffffff',
                customClass: {
                    title: 'fw-bold text-dark',
                }
            });
        }
    };

    if (formSewa) formSewa.addEventListener('submit', validateSubmit);
    if (formOvertime) formOvertime.addEventListener('submit', validateSubmit);
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