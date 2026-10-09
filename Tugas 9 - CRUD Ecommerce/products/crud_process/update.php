<?php
require '../../koneksi_db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] ?? '';
$stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
$category = $_POST['category'] ?? '';
$categories = ['Elektronik', 'Pakaian', 'Makanan'];

if (!$id || $name === '' || $description === '' || !is_numeric($price) || (float) $price < 0 || $stock === false || $stock === null || $stock < 0 || !in_array($category, $categories, true)) {
    http_response_code(400);
    exit('Data produk tidak valid. Periksa kembali semua field.');
}

$statement = $pdo->prepare('SELECT image FROM products WHERE id = :id');
$statement->execute([':id' => $id]);
$product = $statement->fetch();

if (!$product) {
    http_response_code(404);
    exit('Produk tidak ditemukan.');
}

$image = $product['image'];
$newImagePath = null;
$upload = $_FILES['image'] ?? null;

if ($upload && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($upload['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        exit('Gagal mengunggah gambar.');
    }

    if ($upload['size'] > 2 * 1024 * 1024) {
        http_response_code(400);
        exit('Ukuran gambar terlalu besar. Maksimal 2MB.');
    }

    $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
    $imageTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];

    if (!isset($imageTypes[$mimeType])) {
        http_response_code(400);
        exit('Format gambar tidak valid. Gunakan JPEG, PNG, atau GIF.');
    }

    $targetDirectory = __DIR__ . '/../../assets/images/';
    if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
        http_response_code(500);
        exit('Direktori penyimpanan gambar tidak dapat dibuat.');
    }

    $image = uniqid('product_', true) . '.' . $imageTypes[$mimeType];
    $newImagePath = $targetDirectory . $image;
    if (!move_uploaded_file($upload['tmp_name'], $newImagePath)) {
        http_response_code(500);
        exit('Gambar produk gagal disimpan.');
    }
}

$statement = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price, stock = :stock, category = :category, image = :image WHERE id = :id');
$statement->execute([
    ':id' => $id,
    ':name' => $name,
    ':description' => $description,
    ':price' => $price,
    ':stock' => $stock,
    ':category' => $category,
    ':image' => $image,
]);

if ($newImagePath !== null && !empty($product['image'])) {
    $oldImagePath = __DIR__ . '/../../assets/images/' . basename($product['image']);
    if (is_file($oldImagePath)) {
        unlink($oldImagePath);
    }
}

header('Location: ../index.php');
exit();
