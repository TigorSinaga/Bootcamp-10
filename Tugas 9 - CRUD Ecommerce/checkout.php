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

$cart = [];
foreach ($_SESSION['cart'] as $productId => $quantity) {
    $validId = filter_var($productId, FILTER_VALIDATE_INT);
    $validQuantity = filter_var($quantity, FILTER_VALIDATE_INT);
    if ($validId && $validQuantity && $validQuantity > 0) {
        $cart[$validId] = $validQuantity;
    }
}
$_SESSION['cart'] = $cart;

if (!$cart) {
    header('Location: keranjang.php');
    exit;
}

$productIds = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$productStatement = $pdo->prepare("SELECT id, name, price, stock, image FROM products WHERE id IN ($placeholders)");
$productStatement->execute($productIds);
$products = [];
foreach ($productStatement->fetchAll() as $product) {
    $products[(int) $product['id']] = $product;
}

foreach (array_diff($productIds, array_keys($products)) as $missingProductId) {
    unset($cart[$missingProductId], $_SESSION['cart'][$missingProductId]);
}

if (!$cart) {
    header('Location: keranjang.php');
    exit;
}

$customerStatement = $pdo->query("SELECT id, name, phone, address FROM users WHERE role = 'customer' ORDER BY name");
$customers = $customerStatement->fetchAll();
$errors = [];
$values = [
    'user_id' => '',
    'customer_name' => '',
    'customer_phone' => '',
    'shipping_address' => '',
];

if (empty($_SESSION['checkout_csrf'])) {
    $_SESSION['checkout_csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'user_id' => (string) ($_POST['user_id'] ?? ''),
        'customer_name' => trim($_POST['customer_name'] ?? ''),
        'customer_phone' => trim($_POST['customer_phone'] ?? ''),
        'shipping_address' => trim($_POST['shipping_address'] ?? ''),
    ];
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!is_string($csrfToken) || !hash_equals($_SESSION['checkout_csrf'], $csrfToken)) {
        $errors[] = 'Sesi formulir tidak valid. Muat ulang halaman lalu coba kembali.';
    }

    $userId = filter_var($values['user_id'], FILTER_VALIDATE_INT);
    if (!$userId) {
        $errors[] = 'Pilih akun customer yang valid.';
    }
    if ($values['customer_name'] === '' || mb_strlen($values['customer_name']) > 100) {
        $errors[] = 'Nama customer wajib diisi dan maksimal 100 karakter.';
    }
    if ($values['customer_phone'] === '' || mb_strlen($values['customer_phone']) > 20) {
        $errors[] = 'Nomor telepon wajib diisi dan maksimal 20 karakter.';
    }
    if ($values['shipping_address'] === '') {
        $errors[] = 'Alamat pengiriman wajib diisi.';
    }

    if ($userId) {
        $accountStatement = $pdo->prepare("SELECT id FROM users WHERE id = :id AND role = 'customer'");
        $accountStatement->execute([':id' => $userId]);
        if (!$accountStatement->fetchColumn()) {
            $errors[] = 'Akun customer yang dipilih tidak ditemukan.';
        }
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $lockedProductStatement = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($placeholders) ORDER BY id FOR UPDATE");
            $lockedProductStatement->execute($productIds);
            $lockedProducts = [];
            foreach ($lockedProductStatement->fetchAll() as $product) {
                $lockedProducts[(int) $product['id']] = $product;
            }

            if (count($lockedProducts) !== count($cart)) {
                throw new RuntimeException('Salah satu produk keranjang sudah tidak tersedia.');
            }

            $totalCents = 0;
            foreach ($cart as $productId => $quantity) {
                $product = $lockedProducts[$productId];
                if ($quantity > (int) $product['stock']) {
                    throw new RuntimeException('Stok produk ' . $product['name'] . ' tidak mencukupi.');
                }

                $priceParts = explode('.', number_format((float) $product['price'], 2, '.', ''));
                $totalCents += ((int) $priceParts[0] * 100 + (int) $priceParts[1]) * $quantity;
            }

            $totalPrice = number_format($totalCents / 100, 2, '.', '');
            $invoiceToken = bin2hex(random_bytes(32));
            $insertOrder = $pdo->prepare(
                'INSERT INTO orders (user_id, customer_name, customer_phone, total_price, status, shipping_address, invoice_token)
                VALUES (:user_id, :customer_name, :customer_phone, :total_price, :status, :shipping_address, :invoice_token)'
            );
            $insertOrder->execute([
                ':user_id' => $userId,
                ':customer_name' => $values['customer_name'],
                ':customer_phone' => $values['customer_phone'],
                ':total_price' => $totalPrice,
                ':status' => 'pending',
                ':shipping_address' => $values['shipping_address'],
                ':invoice_token' => $invoiceToken,
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $insertItem = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                VALUES (:order_id, :product_id, :quantity, :price, :subtotal)'
            );
            $decreaseStock = $pdo->prepare(
                'UPDATE products SET stock = stock - :decrease_quantity WHERE id = :id AND stock >= :minimum_stock'
            );

            foreach ($cart as $productId => $quantity) {
                $unitPrice = number_format((float) $lockedProducts[$productId]['price'], 2, '.', '');
                $subtotalCents = (int) round((float) $unitPrice * 100) * $quantity;
                $insertItem->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $productId,
                    ':quantity' => $quantity,
                    ':price' => $unitPrice,
                    ':subtotal' => number_format($subtotalCents / 100, 2, '.', ''),
                ]);

                $decreaseStock->execute([
                    ':decrease_quantity' => $quantity,
                    ':id' => $productId,
                    ':minimum_stock' => $quantity,
                ]);
                if ($decreaseStock->rowCount() !== 1) {
                    throw new RuntimeException('Stok produk berubah saat checkout. Silakan periksa keranjang dan coba kembali.');
                }
            }

            $pdo->commit();
            $_SESSION['cart'] = [];
            unset($_SESSION['checkout_csrf']);

            header('Location: invoice.php?' . http_build_query([
                'id' => $orderId,
                'token' => $invoiceToken,
            ]));
            exit;
        } catch (RuntimeException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors[] = $exception->getMessage();
        } catch (PDOException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Checkout database error: ' . $exception->getMessage());
            $errors[] = 'Pesanan gagal disimpan. Silakan coba kembali.';
        }
    }
}

$cartTotal = 0;
foreach ($cart as $productId => $quantity) {
    if (isset($products[$productId])) {
        $cartTotal += (float) $products[$productId]['price'] * $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | Etalase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            color: #202938;
        }

        .checkout-card {
            border: 0;
            border-radius: 1rem;
        }

        .checkout-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            background: #e8edf5;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Etalase</a>
            <a class="btn btn-outline-light" href="keranjang.php">Kembali ke keranjang</a>
        </div>
    </nav>

    <main class="container py-5">
        <h1 class="h2 fw-bold mb-4">Checkout</h1>

        <?php if ($errors): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= escapeHtml($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="checkout.php" class="row g-4">
            <input type="hidden" name="csrf_token" value="<?= escapeHtml($_SESSION['checkout_csrf']) ?>">
            <div class="col-12 col-lg-7">
                <section class="card checkout-card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Informasi Customer dan Pengiriman</h2>
                        <?php if (!$customers): ?>
                            <div class="alert alert-warning mb-0">
                                Belum ada akun customer. Buat akun dengan role customer sebelum checkout.
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Akun customer</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="">Pilih akun customer</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option
                                            value="<?= (int) $customer['id'] ?>"
                                            <?= $values['user_id'] === (string) $customer['id'] ? 'selected' : '' ?>>
                                            Akun customer #<?= (int) $customer['id'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Pesanan akan dikaitkan dengan akun yang dipilih. Formulir ini tidak memverifikasi kepemilikan akun.</div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama customer</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="customer_name"
                                    name="customer_name"
                                    maxlength="100"
                                    value="<?= escapeHtml($values['customer_name']) ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Nomor telepon</label>
                                <input
                                    type="tel"
                                    class="form-control"
                                    id="customer_phone"
                                    name="customer_phone"
                                    maxlength="20"
                                    value="<?= escapeHtml($values['customer_phone']) ?>"
                                    required>
                            </div>

                            <div class="mb-0">
                                <label for="shipping_address" class="form-label">Alamat pengiriman</label>
                                <textarea
                                    class="form-control"
                                    id="shipping_address"
                                    name="shipping_address"
                                    rows="4"
                                    required><?= escapeHtml($values['shipping_address']) ?></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="col-12 col-lg-5">
                <section class="card checkout-card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Review Pesanan</h2>
                        <div class="list-group list-group-flush mb-3">
                            <?php foreach ($cart as $productId => $quantity): ?>
                                <?php
                                $product = $products[$productId];
                                $imageName = trim((string) ($product['image'] ?? ''));
                                $imagePath = $imageName !== ''
                                    ? 'assets/images/' . rawurlencode(basename(str_replace('\\', '/', $imageName)))
                                    : '';
                                ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if ($imagePath !== ''): ?>
                                            <img src="<?= escapeHtml($imagePath) ?>" class="checkout-image rounded" alt="<?= escapeHtml($product['name']) ?>">
                                        <?php else: ?>
                                            <div class="checkout-image rounded d-flex align-items-center justify-content-center text-secondary small text-center">Tanpa gambar</div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold"><?= escapeHtml($product['name']) ?></div>
                                            <small class="text-secondary"><?= (int) $quantity ?> × Rp<?= number_format((float) $product['price'], 0, ',', '.') ?></small>
                                        </div>
                                        <strong class="text-nowrap">Rp<?= number_format((float) $product['price'] * $quantity, 0, ',', '.') ?></strong>
                                    </div>
                                    <?php if ($quantity > (int) $product['stock']): ?>
                                        <div class="small text-danger mt-2">Stok tersedia hanya <?= (int) $product['stock'] ?> unit.</div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3 mb-3">
                            <span>Total pesanan</span>
                            <strong class="text-primary">Rp<?= number_format($cartTotal, 0, ',', '.') ?></strong>
                        </div>
                        <p class="small text-secondary">Pastikan alamat dan detail pesanan sudah benar sebelum membuat pesanan.</p>
                        <button class="btn btn-primary btn-lg w-100" type="submit" <?= !$customers ? 'disabled' : '' ?>>Buat Pesanan</button>
                    </div>
                </section>
            </div>
        </form>
    </main>

</body>

</html>
