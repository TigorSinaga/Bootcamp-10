<?php
session_start();
require_once __DIR__ . '/koneksi_db.php';

function escapeHtml(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['cart_action'] ?? '';
    $productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);

    if (!$productId || !in_array($action, ['update', 'remove'], true)) {
        $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Permintaan keranjang tidak valid.'];
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['cart_message'] = ['type' => 'success', 'text' => 'Produk dihapus dari keranjang.'];
    } else {
        $quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);
        $statement = $pdo->prepare('SELECT stock FROM products WHERE id = :id');
        $statement->execute([':id' => $productId]);
        $stock = $statement->fetchColumn();

        if ($stock === false) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Produk tidak ditemukan dan telah dihapus dari keranjang.'];
        } elseif ($quantity === false || $quantity === null || $quantity < 1) {
            $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Jumlah produk harus minimal 1.'];
        } elseif ($quantity > (int) $stock) {
            $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Jumlah yang diminta melebihi stok yang tersedia.'];
        } else {
            $_SESSION['cart'][$productId] = $quantity;
            $_SESSION['cart_message'] = ['type' => 'success', 'text' => 'Jumlah produk berhasil diperbarui.'];
        }
    }

    header('Location: keranjang.php');
    exit;
}

$cart = [];
foreach ($_SESSION['cart'] as $productId => $quantity) {
    $validId = filter_var($productId, FILTER_VALIDATE_INT);
    $validQuantity = filter_var($quantity, FILTER_VALIDATE_INT);
    if ($validId && $validQuantity && $validQuantity > 0) {
        $cart[$validId] = $validQuantity;
    }
}
$_SESSION['cart'] = $cart;

$products = [];
if ($cart) {
    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $statement = $pdo->prepare("SELECT id, name, description, price, stock, image FROM products WHERE id IN ($placeholders)");
    $statement->execute($productIds);
    foreach ($statement->fetchAll() as $product) {
        $products[(int) $product['id']] = $product;
    }

    $missingProductIds = array_diff($productIds, array_keys($products));
    foreach ($missingProductIds as $missingProductId) {
        unset($_SESSION['cart'][$missingProductId], $cart[$missingProductId]);
    }
}

$cartCount = array_sum($cart);
$cartTotal = 0;
foreach ($cart as $productId => $quantity) {
    if (isset($products[$productId])) {
        $cartTotal += (float) $products[$productId]['price'] * $quantity;
    }
}
$message = $_SESSION['cart_message'] ?? null;
unset($_SESSION['cart_message']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja | Etalase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            color: #202938;
        }

        .cart-card {
            border: 0;
            border-radius: 1rem;
        }

        .cart-image {
            width: 88px;
            height: 88px;
            object-fit: cover;
            background: #e8edf5;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Etalase</a>
            <a class="btn btn-outline-light" href="home.php">Lanjut Belanja</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1">Keranjang Belanja</h1>
                <p class="text-secondary mb-0"><?= (int) $cartCount ?> produk di keranjang</p>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= escapeHtml($message['type']) ?> alert-dismissible fade show" role="alert">
                <?= escapeHtml($message['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?php if (!$cart): ?>
            <section class="card cart-card shadow-sm">
                <div class="card-body p-5 text-center">
                    <h2 class="h4">Keranjang masih kosong</h2>
                    <p class="text-secondary">Jelajahi produk dan tambahkan produk pilihan Anda ke keranjang.</p>
                    <a class="btn btn-primary" href="home.php">Lihat Produk</a>
                </div>
            </section>
        <?php else: ?>
            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-8">
                    <section class="card cart-card shadow-sm">
                        <div class="list-group list-group-flush">
                            <?php foreach ($cart as $productId => $quantity): ?>
                                <?php
                                $product = $products[$productId];
                                $imageName = trim((string) ($product['image'] ?? ''));
                                $imagePath = $imageName !== ''
                                    ? 'assets/images/' . rawurlencode(basename(str_replace('\\', '/', $imageName)))
                                    : '';
                                $subtotal = (float) $product['price'] * $quantity;
                                ?>
                                <div class="list-group-item p-3 p-md-4">
                                    <div class="d-flex flex-column flex-sm-row gap-3">
                                        <?php if ($imagePath !== ''): ?>
                                            <img src="<?= escapeHtml($imagePath) ?>" class="cart-image rounded" alt="<?= escapeHtml($product['name']) ?>">
                                        <?php else: ?>
                                            <div class="cart-image rounded d-flex align-items-center justify-content-center text-secondary small text-center p-2">Gambar tidak tersedia</div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div>
                                                    <h2 class="h5 mb-1">
                                                        <a class="link-dark text-decoration-none" href="detail_product.php?id=<?= (int) $productId ?>">
                                                            <?= escapeHtml($product['name']) ?>
                                                        </a>
                                                    </h2>
                                                    <p class="text-secondary small mb-2">Rp<?= number_format((float) $product['price'], 0, ',', '.') ?> / unit</p>
                                                </div>
                                                <strong class="text-primary text-nowrap">Rp<?= number_format($subtotal, 0, ',', '.') ?></strong>
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                                                <form class="d-flex align-items-end gap-2" method="post" action="keranjang.php">
                                                    <input type="hidden" name="product_id" value="<?= (int) $productId ?>">
                                                    <input type="hidden" name="cart_action" value="update">
                                                    <div>
                                                        <label for="quantity-<?= (int) $productId ?>" class="form-label small mb-1">Jumlah</label>
                                                        <input
                                                            type="number"
                                                            class="form-control form-control-sm"
                                                            id="quantity-<?= (int) $productId ?>"
                                                            name="quantity"
                                                            value="<?= (int) $quantity ?>"
                                                            min="1"
                                                            max="<?= (int) $product['stock'] ?>"
                                                            required>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-primary" type="submit">Perbarui</button>
                                                </form>
                                                <form method="post" action="keranjang.php">
                                                    <input type="hidden" name="product_id" value="<?= (int) $productId ?>">
                                                    <input type="hidden" name="cart_action" value="remove">
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                                </form>
                                            </div>
                                            <small class="text-secondary d-block mt-2">Stok tersedia: <?= (int) $product['stock'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
                <div class="col-12 col-lg-4">
                    <section class="card cart-card shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">Ringkasan Belanja</h2>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-secondary">Total sementara</span>
                                <strong>Rp<?= number_format($cartTotal, 0, ',', '.') ?></strong>
                            </div>
                            <p class="small text-secondary mb-0">Total belum termasuk ongkos kirim.</p>
                            <a class="btn btn-primary w-100 mt-4" href="checkout.php">Lanjut ke Checkout</a>
                        </div>
                    </section>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
