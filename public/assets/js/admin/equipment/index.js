document.addEventListener('DOMContentLoaded', function() {
    // FILTER & SEARCH LOGIC
    const searchInput = document.getElementById('hkSearchInput');
    const catFilter = document.getElementById('hkCategoryFilter');
    const statusFilter = document.getElementById('hkStatusFilter');
    const tableRows = document.querySelectorAll('#hkEquipmentTable tbody .hk-tr-item');

    function executeFilter() {
        const query = searchInput.value.toLowerCase().trim();
        const category = catFilter.value;
        const status = statusFilter.value;

        tableRows.forEach(row => {
            const rowName = row.getAttribute('data-name') || '';
            const rowBrand = row.getAttribute('data-brand') || '';
            const rowId = row.getAttribute('data-id') || '';
            const rowCat = row.getAttribute('data-category');
            const rowStat = row.getAttribute('data-status');

            const matchSearch = rowName.includes(query) || rowBrand.includes(query) || rowId.includes(query);
            const matchCategory = category === "" || rowCat === category;
            const matchStatus = status === "" || rowStat === status;

            row.style.display = (matchSearch && matchCategory && matchStatus) ? "" : "none";
        });
    }

    if(searchInput) searchInput.addEventListener('input', executeFilter);
    if(catFilter) catFilter.addEventListener('change', executeFilter);
    if(statusFilter) statusFilter.addEventListener('change', executeFilter);
});

// FUNGSI UPDATE STATUS DENGAN KONFIRMASI
function toggleMaintenanceInput(selectElement, id) {
    const maintDiv = document.getElementById('maint-input-' + id);
    if (selectElement.value === 'maintenance') {
        maintDiv.classList.remove('d-none');
    } else {
        if (maintDiv) maintDiv.classList.add('d-none');
        confirmUpdateStatus(selectElement.form);
    }
}

function confirmUpdateStatus(form) {
    Swal.fire({
        title: 'Ubah Status?',
        text: "Status unit akan diperbarui di sistem.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Update!',
        background: '#111827',
        color: '#fff',
        reverseButtons:true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else {
            location.reload(); // Reset dropdown jika batal
        }
    });
}

// PREVIEW IMAGE
function previewImage(src, name) {
    if(!src) return;
    const modalElement = document.getElementById('imagePreviewModal');
    const modal = new bootstrap.Modal(modalElement);
    document.getElementById('modalPreviewImg').src = src;
    document.getElementById('modalUnitName').innerText = name;
    modal.show();
}

// EXPAND DESCRIPTION
function expandDesc(element) {
    const isExpanded = element.classList.contains('expanded');
    const fullText = element.getAttribute('data-full');
    const textSpan = element.querySelector('.desc-text');
    const hint = element.querySelector('.hk-click-hint');
    
    if (isExpanded) {
        textSpan.innerText = fullText.substring(0, 35) + '...';
        hint.innerText = "🔍";
        element.classList.remove('expanded');
    } else {
        textSpan.innerText = fullText;
        hint.innerText = "✖";
        element.classList.add('expanded');
    }
}

// FUNGSI KONFIRMASI HAPUS
function confirmDelete(form, unitName) {
    Swal.fire({
        title: 'Hapus Unit?',
        html: `Apakah Anda yakin menghapus <b class="text-warning">${unitName}</b>? Data tidak bisa dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'YA, HAPUS',
        background: '#111827',
        color: '#fff',
        borderRadius: '20px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}