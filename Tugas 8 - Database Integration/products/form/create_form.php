<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>

<body>
    <h1>Tambah Produk</h1>
    <form action="../crud_process/create.php" method="POST">
        <label for="name">Nama:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="category">Kategori:</label>
        <select name="category" id="category" required>
            <option value="">Pilih Kategori</option>
            <option value="Elektronik">Elektronik</option>
            <option value="Pakaian">Pakaian</option>
            <option value="Makanan">Makanan</option>
        </select><br><br>
        <label for="description">Deskripsi:</label>
        <textarea name="description" id="description" required></textarea><br><br>
        <label for="price">Harga:</label>
        <input type="number" name="price" id="price" step="0.01" required><br><br>
        <label for="stock">Stok:</label>
        <input type="number" name="stock" id="stock" required><br><br>
        <label for="image">Gambar:</label>
        <input type="file" name="image" id="image" accept="image/*"><br><br>
        <input type="submit" value="Tambah Produk">
    </form>
</body>

</html>