<?php
require_once __DIR__ . '/koneksi_db.php';

function escapeHtml($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$search = trim($_GET['q'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');

$categoryStatement = $pdo->query(
    "SELECT DISTINCT category
    FROM products
    WHERE category IS NOT NULL AND category <> ''
    ORDER BY category"
);
$categories = $categoryStatement->fetchAll(PDO::FETCH_COLUMN);

$sql = "SELECT * FROM products";
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
            background: linear-gradient(120deg, #132c53, #315fa6);
            color: #fff;
        }

        .product-card {
            border: 0;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
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
    </style>
</head>

<body>
    <header class="store-header py-5">
        <div class="container">
            <p class="text-uppercase small fw-semibold opacity-75 mb-2">Koleksi pilihan</p>
            <h1 class="display-5 fw-bold mb-2">Etalase Produk</h1>
            <p class="mb-0 opacity-75">Temukan produk yang Anda butuhkan.</p>
        </div>
    </header>

    <main class="container py-5">
        <form class="row g-3 align-items-end bg-white p-3 p-md-4 rounded-4 shadow-sm mb-4" method="get" action="">
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
                <select class="form-select form-select-lg" id="category" name="category">
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
            <span class="text-secondary small"><?= count($products) ?> produk ditemukan</span>
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
                                <div class="image-placeholder" role="img" aria-label="Gambar produk tidak tersedia">
                                    Gambar produk belum tersedia
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
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>