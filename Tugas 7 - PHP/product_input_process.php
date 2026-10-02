<?php
$errors = [];
$product = [];
$categories = ['Elektronik', 'Pakaian', 'Makanan', 'Lainnya'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	$errors[] = 'Form harus dikirim terlebih dahulu.';
} else {
	$product['nama_produk'] = trim($_POST['nama_produk'] ?? '');
	$product['harga'] = trim($_POST['harga'] ?? '');
	$product['deskripsi'] = trim($_POST['deskripsi'] ?? '');
	$product['kategori'] = trim($_POST['kategori'] ?? '');
	$product['stok'] = trim($_POST['stok'] ?? '');

	foreach ($product as $value) {
		if ($value === '') {
			$errors[] = 'Semua field wajib diisi.';
			break;
		}
	}

	if ($product['nama_produk'] !== '' && mb_strlen($product['nama_produk']) > 100) {
		$errors[] = 'Nama produk maksimal 100 karakter.';
	}

	if ($product['harga'] !== '' && (!is_numeric($product['harga']) || (float) $product['harga'] <= 0)) {
		$errors[] = 'Harga harus berupa angka lebih dari 0.';
	}

	if ($product['kategori'] !== '' && !in_array($product['kategori'], $categories, true)) {
		$errors[] = 'Kategori yang dipilih tidak valid.';
	}

	if ($product['stok'] !== '' && (!ctype_digit($product['stok']) || (int) $product['stok'] < 0)) {
		$errors[] = 'Stok harus berupa bilangan bulat 0 atau lebih.';
	}

	$image = $_FILES['gambar'] ?? null;
	if (!$image || $image['error'] === UPLOAD_ERR_NO_FILE) {
		$errors[] = 'Gambar produk wajib diunggah.';
	} elseif ($image['error'] !== UPLOAD_ERR_OK) {
		$errors[] = 'Gambar gagal diunggah.';
	} elseif ($image['size'] > 5 * 1024 * 1024) {
		$errors[] = 'Ukuran gambar maksimal 5 MB.';
	} elseif (@getimagesize($image['tmp_name']) === false) {
		$errors[] = 'File yang diunggah harus berupa gambar yang valid.';
	}
}

function escape(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Hasil Input Produk</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<main class="page-shell result-shell">
		<header class="page-header">
			<p class="eyebrow">KATALOG PRODUK</p>
			<h1>Hasil Input Produk</h1>
		</header>

	<?php if ($errors): ?>
		<p class="notice notice-error">Data belum dapat diproses:</p>
		<ul class="error-list">
			<?php foreach (array_unique($errors) as $error): ?>
				<li><?= escape($error) ?></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p class="notice notice-success">Data produk berhasil diterima. Data ini tidak disimpan ke database atau disk.</p>
		<ul class="result-list">
			<li>Nama produk: <?= escape($product['nama_produk']) ?></li>
			<li>Harga: <?= escape($product['harga']) ?></li>
			<li>Deskripsi: <?= nl2br(escape($product['deskripsi'])) ?></li>
			<li>Kategori: <?= escape($product['kategori']) ?></li>
			<li>Stok: <?= escape($product['stok']) ?></li>
			<li>Nama file gambar: <?= escape(basename($image['name'])) ?></li>
		</ul>
	<?php endif; ?>

		<a class="back-link" href="product_input_form.php">Kembali ke form</a>
	</main>
</body>
</html>
