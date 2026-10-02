<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Input Produk</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<main class="page-shell">
		<header class="page-header">
			<p class="eyebrow">KATALOG PRODUK</p>
			<h1>Form Input Produk</h1>
			<p class="page-intro">Lengkapi informasi produk di bawah ini.</p>
		</header>

		<form action="product_input_process.php" method="post" enctype="multipart/form-data">
		<p>
			<label for="nama_produk">Nama produk</label><br>
			<input type="text" id="nama_produk" name="nama_produk" maxlength="100" required>
		</p>

		<p>
			<label for="harga">Harga</label><br>
			<input type="number" id="harga" name="harga" min="0.01" step="0.01" required>
		</p>

		<p>
			<label for="deskripsi">Deskripsi</label><br>
			<textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
		</p>

		<p>
			<label for="kategori">Kategori</label><br>
			<select id="kategori" name="kategori" required>
				<option value="">Pilih kategori</option>
				<option value="Elektronik">Elektronik</option>
				<option value="Pakaian">Pakaian</option>
				<option value="Makanan">Makanan</option>
				<option value="Lainnya">Lainnya</option>
			</select>
		</p>

		<p>
			<label for="stok">Stok</label><br>
			<input type="number" id="stok" name="stok" min="0" step="1" required>
		</p>

		<p>
			<label for="gambar">Gambar produk</label><br>
			<input type="file" id="gambar" name="gambar" accept="image/*" required>
		</p>

		<button type="submit">Kirim produk</button>
		</form>
	</main>
</body>
</html>
