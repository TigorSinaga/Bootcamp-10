<?php

require 'koneksi_db.php';


// create
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $statement = $pdo->prepare($query);
    $statement->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password
    ]);
}

// read
$query = "SELECT * FROM users";
$statement = $pdo->prepare($query);
$statement->execute();
$users = $statement->fetchAll();

// update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
    $statement = $pdo->prepare($query);
    $statement->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':id' => $id
    ]);
}

// delete    
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = :id";
    $statement = $pdo->prepare($query);
    $statement->execute([':id' => $id]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Contoh CRUD</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?= $user['name'] ?> - <?= $user['email'] ?>
                <a href="?delete=<?= $user['id'] ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>