<?php
session_start();
require_once __DIR__ . '/koneksi_db.php';

function escapeHtml(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function escapeUrl(mixed $value): string
{
    return rawurlencode((string) $value);
}
$search = trim($_GET['q'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['cart_action'] ?? '';
    $productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);

    if ($productId && in_array($action, ['add', 'remove'], true)) {
        if ($action === 'add') {
            $checkProduct = $pdo->prepare('SELECT stock FROM products WHERE id = :id');
            $checkProduct->execute([':id' => $productId]);
            $stock = $checkProduct->fetchColumn();
            if ($stock === false) {
                $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Produk tidak ditemukan.'];
            } elseif ((int) ($_SESSION['cart'][$productId] ?? 0) + 1 > (int) $stock) {
                $_SESSION['cart_message'] = ['type' => 'danger', 'text' => 'Stok produk tidak mencukupi.'];
            } else {
                $_SESSION['cart'][$productId] = (int) ($_SESSION['cart'][$productId] ?? 0) + 1;
                $_SESSION['cart_message'] = ['type' => 'success', 'text' => 'Produk berhasil ditambahkan ke keranjang.'];
            }
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    }

    $redirectUrl = 'home.php';
    if ($_GET) {
        $redirectUrl .= '?' . http_build_query($_GET);
    }
    header('Location: ' . $redirectUrl);
    exit;
}

$cartMessage = $_SESSION['cart_message'] ?? null;
unset($_SESSION['cart_message']);

$categoryStatement = $pdo->query(
    "SELECT DISTINCT category
    FROM products
    WHERE category IS NOT NULL AND category <> ''
    ORDER BY category"
);
$categories = $categoryStatement->fetchAll(PDO::FETCH_COLUMN);

$sql = 'SELECT * FROM products';
$conditions = [];
$parameters = [];

if ($selectedCategory !== '') {
    $conditions[] = 'category = :category';
    $parameters[':category'] = $selectedCategory;
}

if ($search !== '') {
    $conditions[] = 'name LIKE :search';
    $parameters[':search'] = '%' . $search . '%';
}

if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= ' ORDER BY id DESC';
$productStatement = $pdo->prepare($sql);
$productStatement->execute($parameters);
$products = $productStatement->fetchAll();

$cartProducts = [];
$cartCount = array_sum($cart);
if ($cart) {
    $cartIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $cartStatement = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $cartStatement->execute($cartIds);
    $cartProducts = $cartStatement->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etalase Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            color: #202938;
        }

        .store-header {
            background: linear-gradient(120deg, #132c53 0%, #315fa6 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .store-header::after {
            content: "";
            position: absolute;
            width: 18rem;
            height: 18rem;
            right: -5rem;
            top: -8rem;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 50%;
            box-shadow: 0 0 0 2rem rgba(255, 255, 255, .04), 0 0 0 4rem rgba(255, 255, 255, .03);
            pointer-events: none;
        }

        .product-card {
            border: 0;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 1rem 2rem rgba(25, 45, 75, .12);
        }

        .product-image {
            height: 220px;
            object-fit: cover;
            background: #e8edf5;
        }

        .image-placeholder {
            height: 220px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #e7edf7, #f5f7fb);
            color: #71809a;
            font-size: .9rem;
        }

        .cart-item+.cart-item {
            border-top: 1px solid #e9ecef;
        }

        @media (prefers-reduced-motion: reduce) {
            .product-card {
                transition: none;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Etalase</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar" aria-controls="storeNavbar" aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="storeNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Keranjang <span class="badge rounded-pill text-bg-primary"><?= $cartCount ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 18rem;">
                            <?php if (!$cartProducts): ?>
                                <p class="text-secondary mb-0">Keranjang masih kosong.</p>
                            <?php else: ?>
                                <?php foreach ($cartProducts as $cartProduct): ?>
                                    <div class="cart-item py-2 d-flex justify-content-between gap-3">
                                        <div>
                                            <div class="fw-semibold"><?= escapeHtml($cartProduct['name']) ?></div>
                                            <small class="text-secondary"><?= (int) $cart[$cartProduct['id']] ?> × Rp<?= number_format((float) $cartProduct['price'], 0, ',', '.') ?></small>
                                        </div>
                                        <form method="post" action="">
                                            <input type="hidden" name="product_id" value="<?= (int) $cartProduct['id'] ?>">
                                            <input type="hidden" name="cart_action" value="remove">
                                            <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus <?= escapeHtml($cartProduct['name']) ?> dari keranjang">Hapus</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <a class="btn btn-sm btn-primary w-100 mt-2" href="keranjang.php">Lihat keranjang</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="store-header py-5">
        <div class="container position-relative">
            <p class="text-uppercase small fw-semibold opacity-75 mb-2">Koleksi pilihan</p>
            <h1 class="display-5 fw-bold mb-2">Etalase Produk</h1>
            <p class="mb-0 opacity-75">Temukan produk yang Anda butuhkan.</p>
        </div>
    </header>

    <main class="container py-5">
        <?php if ($cartMessage): ?>
            <div class="alert alert-<?= escapeHtml($cartMessage['type']) ?> alert-dismissible fade show" role="alert">
                <?= escapeHtml($cartMessage['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <form id="product-filters" class="row g-3 align-items-end bg-white p-3 p-md-4 rounded-4 shadow-sm mb-4" method="get" action="">
            <div class="col-12 col-md-6">
                <label for="q" class="form-label fw-semibold">Cari nama produk</label>
                <input
                    type="search"
                    class="form-control form-control-lg"
                    id="q"
                    name="q"
                    value="<?= escapeHtml($search) ?>"
                    placeholder="Contoh: headphone">
            </div>
            <div class="col-12 col-md-4">
                <label for="category" class="form-label fw-semibold">Kategori</label>
                <select class="form-select form-select-lg" id="category" name="category" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= escapeHtml($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>>
                            <?= escapeHtml($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-primary btn-lg" type="submit">Tampilkan</button>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Produk</h2>
            <span class="badge rounded-pill text-bg-primary px-3 py-2"><?= count($products) ?> produk ditemukan</span>
        </div>

        <?php if (!$products): ?>
            <div class="bg-white rounded-4 shadow-sm p-5 text-center">
                <h3 class="h5">Produk tidak ditemukan</h3>
                <p class="text-secondary mb-0">Coba ubah kata pencarian atau pilih kategori lain.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <?php
                    $imageName = trim((string) ($product['image'] ?? ''));
                    $imagePath = $imageName !== ''
                        ? 'assets/images/' . rawurlencode(basename(str_replace('\\', '/', $imageName)))
                        : '';
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <article class="card product-card h-100 shadow-sm">
                            <?php if ($imagePath !== ''): ?>
                                <img
                                    src="<?= escapeHtml($imagePath) ?>"
                                    class="card-img-top product-image"
                                    alt="<?= escapeHtml($product['name']) ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="image-placeholder" role="img" aria-label="Tidak ada gambar">
                                    Tidak ada gambar
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <?php if (!empty($product['category'])): ?>
                                    <span class="badge text-bg-light align-self-start mb-2"><?= escapeHtml($product['category']) ?></span>
                                <?php endif; ?>
                                <h3 class="h5 card-title"><?= escapeHtml($product['name'] ?? '') ?></h3>
                                <p class="card-text text-secondary small flex-grow-1">
                                    <?= escapeHtml($product['description'] ?? '') ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <strong class="text-primary">Rp<?= number_format((float) ($product['price'] ?? 0), 0, ',', '.') ?></strong>
                                    <span class="small text-secondary">Stok: <?= escapeHtml($product['stock'] ?? 0) ?></span>
                                </div>
                                <a class="btn btn-outline-primary w-100 mt-3" href="detail_product.php?id=<?= urlencode((string) $product['id']) ?>">Lihat detail</a>
                                <form method="post" action="" class="mt-3">
                                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                    <input type="hidden" name="cart_action" value="add">
                                    <button class="btn btn-primary w-100" type="submit">Tambah ke keranjang</button>
                                </form>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>