<?php
// menampilkan data produk dari database
require '../../koneksi_db.php';

$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->execute(['id' => $id]);
$product = $statement->fetch();

if (!$product) {
    exit('Produk tidak ditemukan.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
</head>

<body>
    <h1>Edit Produk</h1>
    <form action="../crud_process/update.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>">
        <label for="name">Nama:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>
        <label for="description">Deskripsi:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') ?></textarea><br><br>
        <label for="price">Harga:</label>
        <input type="number" name="price" id="price" value="<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?>" step="0.01" required><br><br>
        <label for="stock">Stok:</label>
        <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock'], ENT_QUOTES, 'UTF-8') ?>" min="0" required><br><br>
        <label for="category">Kategori:</label>
        <input type="text" name="category" id="category" value="<?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>
        <button type="submit">Update Produk</button>
    </form>
</body>

</html>