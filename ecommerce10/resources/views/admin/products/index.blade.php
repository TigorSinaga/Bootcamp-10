<x-app-layout>
    <div class="product-admin-page">
        <div class="product-admin-heading">
            <div>
                <span class="dashboard-kicker">MANAJEMEN TOKO</span>
                <h1>Produk</h1>
                <p>Kelola nama, deskripsi, stok, harga, gambar, dan kategori produk.</p>
            </div>
            <a href="{{ route('products.create') }}" class="product-admin-add">＋ Tambah Produk</a>
        </div>

        @if (session('success'))
        <div class="product-admin-alert">{{ session('success') }}</div>
        @endif

        <section class="product-admin-panel">
            <div class="product-admin-panel-head">
                <div>
                    <h2>Daftar Produk</h2>
                    <span>{{ $products->total() }} produk terdaftar</span>
                </div>
            </div>
            <form action="{{ route('products.index') }}" method="GET" class="product-admin-filter-bar">
                <div class="product-admin-filter-field product-admin-search-field">
                    <label for="product-search-filter">Cari produk</label>
                    <div class="product-admin-search-input"><span aria-hidden="true">⌕</span><input id="product-search-filter" type="search" name="search" placeholder="Masukkan nama produk..." value="{{ request('search') }}"></div>
                </div>
                <div class="product-admin-filter-field">
                    <label for="category-filter">Kategori</label>
                    <select id="category-filter" name="category">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="product-admin-filter-field">
                    <label for="sort-filter">Urutkan</label>
                    <select id="sort-filter" name="sort">
                        <option value="">Terbaru</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama A → Z</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama Z → A</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga terendah</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga tertinggi</option>
                        <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>Stock terendah</option>
                        <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Stock tertinggi</option>
                    </select>
                </div>
                <div class="product-admin-filter-actions">
                    @if (request()->filled('search') || request()->filled('category') || request()->filled('sort'))
                    <a href="{{ route('products.index') }}" class="product-admin-reset">Reset</a>
                    @endif
                    <button type="submit" class="product-admin-filter-button">Filter</button>
                </div>
            </form>


            <div class="product-admin-table-wrap">
                <table class="product-admin-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td><strong class="product-admin-name">{{ $product->name }}</strong></td>
                            <td>
                                <span class="product-admin-description" title="{{ $product->description }}">
                                    {{ $product->description ?: '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="product-stock {{ $product->stock <= 10 ? 'low' : '' }}">{{ $product->stock }}</span>
                            </td>
                            <td><strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            <td>
                                @if ($product->image)
                                <img class="product-admin-image" src="{{ asset('storage/' . $product->image) }}" data-fallback="{{ asset('images/product-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.dataset.fallback" alt="{{ $product->name }}">
                                @else
                                <img class="product-admin-image" src="{{ asset('images/product-placeholder.svg') }}" alt="Gambar belum tersedia">
                                @endif
                            </td>
                            <td>
                                <span class="product-category-badge">{{ $product->product_category?->name ?? 'Tanpa kategori' }}</span>
                            </td>
                            <td>
                                <div class="product-admin-actions">
                                    <a href="{{ route('products.show', $product) }}" class="product-action view" title="Lihat produk" aria-label="Lihat {{ $product->name }}">👁</a>
                                    <a href="{{ route('products.edit', $product) }}" class="product-action edit" title="Edit produk">✎</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="product-action delete" title="Hapus produk">⌫</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="product-admin-empty">Belum ada produk. Klik <strong>Tambah Produk</strong> untuk menambahkan produk pertama.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
            <div class="product-admin-pagination">{{ $products->onEachSide(1)->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>