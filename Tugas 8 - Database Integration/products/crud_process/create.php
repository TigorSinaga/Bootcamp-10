<?php
require '../../koneksi_db.php';

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
// $image = $_FILES['image']['name'];

// move_uploaded_file($_FILES['image']['tmp_name'], '../../assets/images/' . $image);

$query = "INSERT INTO products (name, description, price, stock, category) VALUES (:name, :description, :price, :stock, :category)";
$statement = $pdo->prepare($query);
$statement->execute([
    ':name' => $name,
    ':description' => $description,
    ':price' => $price,
    ':stock' => $stock,
    ':category' => $category,
    // ':image' => $image
]);

header('Location: ../index.php');
exit();
