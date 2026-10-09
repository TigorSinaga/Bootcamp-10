<?php
require '../../koneksi_db.php';

$id = $_GET['id'];
$query = "DELETE FROM products WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->execute(['id' => $id]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    //data validation can be added here if needed
    $query = "DELETE FROM products WHERE id = :id";
    $statement = $pdo->prepare($query);
    $statement->execute(['id' => $id]);
}

// delete the iimage file associated with the product
$query = "SELECT image FROM products WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->execute(['id' => $id]);
$product = $statement->fetch();

if ($product) {
    $image_path = "../../assets/images/" . $product['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

header('Location: ../index.php');
exit();
