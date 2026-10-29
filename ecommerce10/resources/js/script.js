const loadScript = (src) =>
    new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }

        const script = document.createElement("script");
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });

const initializeProductCropper = ({ inputSelector, targetSelector, previewSelector, required = false }) => {
    const input = document.querySelector(inputSelector);
    const target = document.querySelector(targetSelector);
    if (!input || !target || !window.Croppie || input.dataset.cropperInitialized === 'true') return;
    input.dataset.cropperInitialized = 'true';

    let cropper;
    const hiddenImage = document.querySelector('#cropped-image');
    const form = input.closest('form');
    const preview = document.querySelector(previewSelector);

    input.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            input.setCustomValidity('Gunakan gambar JPG, PNG, atau WEBP.');
            input.reportValidity();
            input.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            input.setCustomValidity('Ukuran gambar maksimal 5 MB.');
            input.reportValidity();
            input.value = '';
            return;
        }
        input.setCustomValidity('');
        hiddenImage.value = '';

        const reader = new FileReader();
        reader.onload = () => {
            if (preview) preview.hidden = true;
            target.hidden = false;
            target.innerHTML = '';
            if (cropper) cropper.destroy();
            requestAnimationFrame(() => {
                cropper = new window.Croppie(target, {
                    viewport: { width: 280, height: 280, type: 'square' },
                    boundary: { width: 320, height: 320 },
                    enableExif: true,
                });
                cropper.bind({ url: reader.result }).catch((error) => {
                    console.error('Croppie gagal memuat gambar:', error);
                });
            });
        };
        reader.readAsDataURL(file);
    });

    form?.addEventListener('submit', async (event) => {
        if (!cropper) {
            if (required && !input.files.length) {
                event.preventDefault();
                input.setCustomValidity('Pilih dan potong gambar produk.');
                input.reportValidity();
            }
            return;
        }
        if (hiddenImage.value) return;

        event.preventDefault();
        const result = await cropper.result({ type: 'base64', size: 'viewport', format: 'jpeg', quality: 0.9 });
        hiddenImage.value = result;
        form.submit();
    });
};

const initializeProductImageCroppers = () => {
    initializeProductCropper({
        inputSelector: '#product-image-input',
        targetSelector: '#product-cropper',
        previewSelector: '#product-image-preview',
        required: true,
    });
    initializeProductCropper({
        inputSelector: '#edit-product-image-input',
        targetSelector: '#edit-product-cropper',
        previewSelector: '#edit-product-image-preview',
    });
};

const initializeCategoryModal = () => {
    const modalElement = document.querySelector('#createCategoryModal');
    const openButtons = document.querySelectorAll('[data-category-modal-open]');

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            window.bootstrap?.Modal.getOrCreateInstance(modalElement).show();
        });
    });
    if (!modalElement || modalElement.dataset.validationError !== 'true') return;

    if (window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
};

const initializeEditCategoryModal = () => {
    const modalElement = document.querySelector('#editCategoryModal');
    if (!modalElement) return;

    document.querySelectorAll('[data-category-edit-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelector('#editCategoryForm').action = button.dataset.categoryUpdateUrl;
            document.querySelector('#edit-category-name').value = button.dataset.categoryName;
            window.bootstrap?.Modal.getOrCreateInstance(modalElement).show();
        });
    });
};

const initializeCategoryTable = async () => {
    const table = document.querySelector("#category-table");
    if (!table || !window.location.pathname.includes("/product-categories"))
        return;

    await loadScript("https://code.jquery.com/jquery-3.7.1.min.js");
    await loadScript(
        "https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js",
    );

    window.jQuery(table).DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, "asc"]],
        columnDefs: [{ orderable: false, targets: 4 }],
        language: {
            search: "Cari kategori:",
            lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ kategori",
            infoEmpty: "Tidak ada kategori",
            zeroRecords: "Kategori tidak ditemukan",
            paginate: { previous: "Sebelumnya", next: "Berikutnya" },
        },
    });
};

const initializePageScripts = () => {
    initializeProductImageCroppers();
    initializeCategoryModal();
    initializeEditCategoryModal();
    initializeCategoryTable();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializePageScripts, { once: true });
} else {
    initializePageScripts();
}

window.addEventListener('load', () => {
    if (!document.querySelector('#product-image-input')?.dataset.cropperInitialized) {
        initializePageScripts();
    }
});
