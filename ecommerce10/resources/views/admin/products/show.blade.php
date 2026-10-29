<x-app-layout>
    <div class="product-admin-page">
        <div class="product-admin-heading">
            <div>
                <span class="dashboard-kicker">DETAIL PRODUK</span>
                <h1>{{ $product->name }}</h1>
                <p>Informasi lengkap produk dan status persediaannya.</p>
            </div>
            <a href="{{ route('products.index') }}" class="product-admin-reset">← Kembali</a>
        </div>

        <section class="product-detail-panel">
            <div class="product-detail-image-wrap">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" data-fallback="{{ asset('images/product-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.dataset.fallback" alt="{{ $product->name }}">
                @else
                    <img src="{{ asset('images/product-placeholder.svg') }}" alt="Gambar belum tersedia">
                @endif
            </div>
            <div class="product-detail-content">
                <span class="product-category-badge">{{ $product->product_category?->name ?? 'Tanpa kategori' }}</span>
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
                <div class="product-detail-stats">
                    <div><span>Harga</span><strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong></div>
                    <div><span>Stock</span><strong>{{ $product->stock }}</strong></div>
                </div>
                <div class="product-detail-actions">
                    <a href="{{ route('products.edit', $product) }}" class="product-admin-add">Edit Produk</a>
                    <a href="{{ route('products.index') }}" class="product-detail-back">Kembali ke daftar</a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
