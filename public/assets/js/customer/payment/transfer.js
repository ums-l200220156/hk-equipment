/**
 * Fungsi Salin Nomor Rekening
 * Diletakkan secara global agar bisa dipanggil oleh atribut onclick di HTML.
 * Dilengkapi dengan fallback untuk browser mobile yang memblokir Clipboard API.
 */
function copyToClipboard() {
    const accountNum = document.getElementById('accountNum').innerText;
    
    // Coba gunakan Clipboard API modern terlebih dahulu
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(accountNum).then(() => {
            showSuccessToast();
        }).catch(err => {
            console.error('Gagal menyalin dengan API: ', err);
            fallbackCopyText(accountNum);
        });
    } else {
        // Gunakan metode cadangan jika API tidak tersedia (Mobile/Non-HTTPS)
        fallbackCopyText(accountNum);
    }
}

/**
 * Fungsi Cadangan untuk menyalin teks (Penting untuk Mobile)
 */
function fallbackCopyText(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Pastikan elemen tidak terlihat tapi tetap bisa diakses sistem
    textArea.style.position = "fixed";
    textArea.style.left = "-9999px";
    textArea.style.top = "0";
    document.body.appendChild(textArea);
    
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showSuccessToast();
        }
    } catch (err) {
        console.error('Fallback gagal menyalin', err);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Fungsi untuk memicu notifikasi SweetAlert
 */
function showSuccessToast() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil disalin!',
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: 'top-end'
        });
    } else {
        alert('Nomor rekening berhasil disalin!');
    }
}

/**
 * Logika Upload, Drag & Drop, dan Validasi Form
 */
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileName');
    const formSewa = document.getElementById('formTransferSewa');
    const formOvertime = document.getElementById('formTransferOvertime');

    if (uploadArea && fileInput) {
        
        // Klik area untuk upload
        uploadArea.addEventListener('click', () => fileInput.click());

        // Drag & Drop event preventions
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        // Efek visual saat file ditarik ke atas area
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => uploadArea.classList.add('active'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                if (!fileInput.files.length) uploadArea.classList.remove('active');
            }, false);
        });

        // Menangani file yang dijatuhkan (drop)
        uploadArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files; 
                handleFile(files[0]);    
            }
        });

        // Menangani pemilihan file lewat tombol/input
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFile(this.files[0]);
            }
        });

        /**
         * Fungsi mengolah file dan menampilkan preview
         */
        function handleFile(file) {
            if (file) {
                fileNameDisplay.innerText = "File terpilih: " + file.name;
                uploadArea.classList.add('active');

                // Jika file adalah gambar, tampilkan previewnya
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
                        
                        // Sembunyikan icon cloud agar tidak menumpuk dengan preview
                        const cloudIcon = uploadArea.querySelector('.bi-cloud-arrow-up-fill');
                        if (cloudIcon) cloudIcon.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    }

    /**
     * Fungsi Validasi sebelum Submit Form
     */
    const validateSubmit = (e) => {
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Bukti Belum Diunggah!',
                text: 'Harap lampirkan foto atau screenshot bukti transfer Anda terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Oke, Saya Paham'
            });
        }
    };

    // Pasang listener pada form sewa maupun overtime
    if (formSewa) formSewa.addEventListener('submit', validateSubmit);
    if (formOvertime) formOvertime.addEventListener('submit', validateSubmit);
});