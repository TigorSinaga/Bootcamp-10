<?php
require '../../koneksi_db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('ID produk tidak valid.');
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->execute([':id' => $id]);
$product = $statement->fetch();

if (!$product) {
    http_response_code(404);
    exit('Produk tidak ditemukan.');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3 mb-2">Edit Produk</h1>
                        <p class="text-secondary mb-4">Perbarui informasi produk yang dipilih.</p>

                        <form action="../crud_process/update.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $product['id'], ENT_QUOTES, 'UTF-8') ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" id="name" value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Masukkan nama produk" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" name="category" id="category" required>
                                    <option value="" disabled <?= $product['category'] === '' ? 'selected' : '' ?>>Pilih kategori</option>
                                    <?php foreach (['Elektronik', 'Pakaian', 'Makanan'] as $category): ?>
                                        <option value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>" <?= $product['category'] === $category ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Deskripsikan produk" required><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="price" id="price" value="<?= htmlspecialchars((string) $product['price'], ENT_QUOTES, 'UTF-8') ?>" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control" name="stock" id="stock" value="<?= htmlspecialchars((string) $product['stock'], ENT_QUOTES, 'UTF-8') ?>" min="0" placeholder="Jumlah stok" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Gambar</label>
                                <?php if (!empty($product['image'])): ?>
                                    <div class="mb-2">
                                        <img src="../../assets/images/<?= rawurlencode(basename($product['image'])) ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" class="img-thumbnail" style="max-width: 140px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="image" id="image" accept="image/jpeg,image/png,image/gif">
                                <div class="form-text">Pilih gambar baru jika ingin mengganti gambar saat ini. Maksimal 2MB.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                            <a href="../index.php" class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <script>
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

            const image = document.getElementById('image').files[0];
            if (image) {
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validImageTypes.includes(image.type)) {
                    alert('Format gambar tidak valid. Gunakan JPEG, PNG, atau GIF.');
                    return false;
                }
                if (image.size > 2 * 1024 * 1024) {
                    alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
                    return false;
                }
            }

            return true;
        }
    </script>
</body>

</html>
