<?php
require '../../koneksi_db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];

$query = "UPDATE products SET name = :name, description = :description, price = :price, stock = :stock, category = :category WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->execute([
    ':id' => $id,
    ':name' => $name,
    ':description' => $description,
    ':price' => $price,
    ':stock' => $stock,
    ':category' => $category
]);

// request validation
if (empty($name) || empty($description) || empty($price) || empty($stock)) {
    echo "Data tidak boleh kosong. Silakan isi semua field.";
    exit();
}

header('Location: ../index.php');
exit();
