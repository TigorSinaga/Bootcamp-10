<?php
// menampilkan data produk dari database
require '../koneksi_db.php';

$query = "SELECT * FROM products";
$statement = $pdo->prepare($query);
$statement->execute();
$products = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1">Daftar Produk</h1>
                <p class="text-secondary mb-0">Kelola produk toko Anda</p>
            </div>
            <a href="form/create_form.php" class="btn btn-primary">Tambah Produk</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="py-3">Deskripsi</th>
                            <th scope="col" class="py-3">Thumbnail</th>

                            <th scope="col" class="py-3">Harga</th>
                            <th scope="col" class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-5">Belum ada produk.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="px-4 fw-semibold"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-nowrap">
                                        <img src="../assets/images/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" class="img-thumbnail" style="max-width: 100px;">
                                    </td>
                                    <td class="text-nowrap">Rp<?= number_format((int) $product['price'], 0, ',', '.') ?></td>
                                    <td class="text-end text-nowrap pe-4">
                                        <a href="form/edit_form.php?id=<?= urlencode($product['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="crud_process/delete.php?id=<?= urlencode($product['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>