<?php
require '../../koneksi_db.php';

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
$target_dir = "../../assets/images/";
// $image = $_FILES['image']['name'];
// create upload directory if it doesn't exist
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}
$image = uniqid() . '_' . basename($_FILES['image']['name']);

$query = "INSERT INTO products (name, description, price, stock, category, image) VALUES (:name, :description, :price, :stock, :category, :image)";
$statement = $pdo->prepare($query);
$statement->execute([
    ':name' => $name,
    ':description' => $description,
    ':price' => $price,
    ':stock' => $stock,
    ':category' => $category,
    ':image' => $image
]);

if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image)) {
    echo "The file " . htmlspecialchars(basename($_FILES['image']['name'])) . " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}

header('Location: ../index.php');
exit();
