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

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeCategoryTable);
} else {
    initializeCategoryTable();
}
