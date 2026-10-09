<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3 mb-2">Tambah Produk</h1>
                        <p class="text-secondary mb-4">Lengkapi informasi produk yang akan ditambahkan.</p>

                        <form action="../crud_process/create.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama produk" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" name="category" id="category" required>
                                    <option value="" selected disabled>Pilih kategori</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Pakaian">Pakaian</option>
                                    <option value="Makanan">Makanan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Deskripsikan produk" required></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="price" id="price" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control" name="stock" id="stock" min="0" placeholder="Jumlah stok" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Gambar</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                <div class="form-text">Unggah gambar produk (opsional).</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Tambah Produk</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <script>
        // Validate form inputs before submission
        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const category = document.getElementById('category').value;
            const description = document.getElementById('description').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const stock = parseInt(document.getElementById('stock').value, 10);

            if (!name || !category || !description || isNaN(price) || isNaN(stock)) {
                alert('Semua field wajib diisi dengan benar.');
                return false;
            }

            if (price < 0 || stock < 0) {
                alert('Harga dan stok tidak boleh negatif.');
                return false;
            }
            if (document.getElementById('image').files.length > 0) {
                const file = document.getElementById('image').files[0];
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validImageTypes.includes(file.type)) {
                    alert('Format gambar tidak valid. Gunakan JPEG, PNG, atau GIF.');
                    return false;
                }
            }
            // validate image size (max 2MB)
            if (document.getElementById('image').files.length > 0) {
                const file = document.getElementById('image').files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
                    return false;
                }
            }

            return true;
        }
    </script>
</body>

</html>