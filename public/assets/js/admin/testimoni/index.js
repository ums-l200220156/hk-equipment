/**
 * 1. FUNGSI UNTUK MELIHAT MEDIA (POPUP)
 * Digunakan untuk menampilkan foto atau video secara dinamis di modal
 */
function viewMedia(url, type) {
    const container = document.getElementById('mediaContainer');
    const myModal = new bootstrap.Modal(document.getElementById('mediaModal'));
    
    container.innerHTML = ''; // Reset isi container

    if (type === 'image') {
        container.innerHTML = `<img src="${url}" class="img-fluid animate__animated animate__zoomIn">`;
    } else if (type === 'video') {
        container.innerHTML = `
            <video controls autoplay class="w-100 animate__animated animate__zoomIn">
                <source src="${url}" type="video/mp4">
                Your browser does not support the video tag.
            </video>`;
    }

    myModal.show();
}

/**
 * 2. KONFIRMASI HAPUS DENGAN SWEETALERT2
 */
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Testimoni?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}