document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('drop-area');
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('placeholder-content');

    // Trigger click input file saat area diklik
    uploadArea.addEventListener('click', () => imageInput.click());

    // Preview Image
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
                uploadArea.style.padding = '10px';
                uploadArea.style.borderStyle = 'solid';
            }
            reader.readAsDataURL(file);
        }
    });

    // Hover effect
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#3b82f6';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#cbd5e1';
    });
});