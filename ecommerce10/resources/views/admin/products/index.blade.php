<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush
    <div class="product-admin-page">
        <div class="db-header admin-index-header">
            <div class="db-header-left">
                <h1>Manajemen Produk</h1>
                <p>Kelola katalog toko dengan ringkasan stok dan kategori secara real-time.</p>
                <span class="db-header-badge">Live</span>
            </div>
            <a href="{{ route('products.create') }}" class="product-admin-add">＋ Tambah Produk</a>
        </div>
        <div class="db-stats admin-index-stats">
            <div class="db-stat"><div class="db-stat-icon icon-purple">📦</div><div class="db-stat-body"><div class="db-stat-label">Total Produk</div><div class="db-stat-value rb-count-up" data-target="{{ $products->total() }}">0</div><div class="db-stat-sub">Produk terdaftar</div></div></div>
            <div class="db-stat"><div class="db-stat-icon icon-green">📊</div><div class="db-stat-body"><div class="db-stat-label">Total Stok</div><div class="db-stat-value rb-count-up" data-target="{{ $totalStock }}">0</div><div class="db-stat-sub">Unit tersedia</div></div></div>
            <div class="db-stat"><div class="db-stat-icon icon-orange">⚠️</div><div class="db-stat-body"><div class="db-stat-label">Stok Rendah</div><div class="db-stat-value rb-count-up" data-target="{{ $lowStockCount }}">0</div><div class="db-stat-sub">Produk perlu restock</div></div></div>
            <div class="db-stat"><div class="db-stat-icon icon-blue">🏷️</div><div class="db-stat-body"><div class="db-stat-label">Tanpa Kategori</div><div class="db-stat-value rb-count-up" data-target="{{ $uncategorizedCount }}">0</div><div class="db-stat-sub">Produk belum dikelompokkan</div></div></div>
        </div>
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
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="rb-delete-form" data-delete-id="{{ $product->id }}" data-delete-url="{{ route('products.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="product-action delete rb-delete-trigger" title="Hapus produk" data-delete-id="{{ $product->id }}" data-delete-name="{{ e($product->name) }}" data-delete-type="produk">⌫</button>
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
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content delete-confirm-modal">
                <div class="delete-confirm-icon" aria-hidden="true">!</div>
                <h2 id="deleteConfirmModalLabel">Hapus <span id="delete-confirm-type">item</span> ini?</h2>
                <p><strong id="delete-confirm-name"></strong> akan dihapus secara permanen. Apakah Anda yakin?</p>
                <div class="delete-confirm-actions">
                    <button type="button" class="delete-confirm-cancel" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="delete-confirm-submit" id="delete-confirm-submit">Ya, hapus</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>