<?php
session_start();
require_once __DIR__ . '/koneksi_db.php';

function escapeHtml(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('ID produk tidak valid.');
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->execute([':id' => $id]);
$product = $statement->fetch();

if (!$product) {
    http_response_code(404);
    exit('Produk tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);

    if ($quantity === false || $quantity === null || $quantity < 1) {
        $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Jumlah produk harus minimal 1.'];
    } elseif ($quantity > (int) $product['stock']) {
        $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Jumlah yang diminta melebihi stok yang tersedia.'];
    } else {
        $currentQuantity = (int) ($_SESSION['cart'][$id] ?? 0);
        if ($currentQuantity + $quantity > (int) $product['stock']) {
            $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Jumlah di keranjang melebihi stok yang tersedia.'];
        } else {
            $_SESSION['cart'][$id] = $currentQuantity + $quantity;
            $_SESSION['cart_message'] = ['type' => 'success', 'text' => 'Produk berhasil ditambahkan ke keranjang.'];
        }
    }

    header('Location: keranjang.php');
    exit;
}

$imageName = trim((string) ($product['image'] ?? ''));
$imagePath = $imageName !== ''
    ? 'assets/images/' . rawurlencode(basename(str_replace('\\', '/', $imageName)))
    : '';
$cartCount = array_sum($_SESSION['cart'] ?? []);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escapeHtml($product['name']) ?> | Etalase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            color: #202938;
        }

        .product-detail {
            border: 0;
            border-radius: 1rem;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            min-height: 320px;
            max-height: 520px;
            object-fit: cover;
            background: #e8edf5;
        }

        .image-placeholder {
            min-height: 320px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #e7edf7, #f5f7fb);
            color: #71809a;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Etalase</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-light" href="home.php">Kembali ke produk</a>
                <a class="btn btn-primary" href="keranjang.php">Keranjang <span class="badge text-bg-light"><?= (int) $cartCount ?></span></a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <article class="card product-detail shadow-sm">
            <div class="row g-0">
                <div class="col-12 col-lg-6">
                    <?php if ($imagePath !== ''): ?>
                        <img src="<?= escapeHtml($imagePath) ?>" class="product-image h-100" alt="<?= escapeHtml($product['name']) ?>">
                    <?php else: ?>
                        <div class="image-placeholder h-100" role="img" aria-label="Gambar produk tidak tersedia">
                            Gambar produk belum tersedia
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card-body p-4 p-md-5">
                        <?php if (!empty($product['category'])): ?>
                            <span class="badge text-bg-light mb-3"><?= escapeHtml($product['category']) ?></span>
                        <?php endif; ?>
                        <h1 class="h2 fw-bold mb-3"><?= escapeHtml($product['name']) ?></h1>
                        <p class="display-6 text-primary fw-semibold mb-3">Rp<?= number_format((float) $product['price'], 0, ',', '.') ?></p>
                        <p class="text-secondary mb-4"><?= nl2br(escapeHtml($product['description'])) ?></p>
                        <p class="mb-4">
                            <span class="fw-semibold">Stok tersedia:</span>
                            <?= (int) $product['stock'] ?> unit
                        </p>

                        <form method="post" action="detail_product.php?id=<?= (int) $product['id'] ?>">
                            <div class="row g-3 align-items-end">
                                <div class="col-4 col-sm-3">
                                    <label for="quantity" class="form-label">Jumlah</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="quantity"
                                        name="quantity"
                                        value="1"
                                        min="1"
                                        max="<?= (int) $product['stock'] ?>"
                                        required
                                        <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>
                                        <?= (int) $product['stock'] < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    </main>
</body>

</html>