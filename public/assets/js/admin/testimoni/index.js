/**
 * 1. FUNGSI FILTER RATING (CLIENT SIDE)
 */
function filterTestimonis() {
    const filterValue = document.getElementById('filterRating').value;
    const rows = document.querySelectorAll('.testimoni-row');

    rows.forEach(row => {
        const rating = row.getAttribute('data-rating');
        if (filterValue === 'all' || filterValue === rating) {
            row.style.display = ''; // Tampilkan
        } else {
            row.style.display = 'none'; // Sembunyikan
        }
    });
}

/**
 * 2. FUNGSI UNTUK MELIHAT MEDIA (POPUP)
 */
function viewMedia(url, type) {
    const container = document.getElementById('mediaContainer');
    const modalEl = document.getElementById('mediaModal');
    if (!modalEl) return;

    const myModal = new bootstrap.Modal(modalEl);
    
    container.innerHTML = ''; 

    if (type === 'image') {
        container.innerHTML = `<img src="${url}" class="img-fluid rounded-4 shadow">`;
    } else if (type === 'video') {
        container.innerHTML = `
            <video controls autoplay class="w-100 rounded-4 shadow">
                <source src="${url}" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>`;
    }

    myModal.show();
}

/**
 * 3. KONFIRMASI HAPUS
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