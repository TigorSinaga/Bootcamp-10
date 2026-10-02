<?php
require '../../koneksi_db.php';

$id = $_GET['id'];
$query = "DELETE FROM products WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->execute(['id' => $id]);

header('Location: ../index.php');
exit();
