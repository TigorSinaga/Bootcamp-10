<x-app-layout>
    <div class="category-admin-page">
        <div class="category-admin-heading">
            <div>
                <span class="dashboard-kicker">MANAJEMEN TOKO</span>
                <h1>Product Categories</h1>
                <p>Kelola kategori untuk mengelompokkan produk toko.</p>
            </div>
            <a href="{{ route('product-categories.create') }}" class="category-admin-add">＋ Tambahkan Kategori Baru</a>
        </div>

        @if (session('success'))
        <div class="category-admin-alert">{{ session('success') }}</div>
        @endif

        <section class="category-admin-panel">
            <div class="category-admin-panel-head">
                <div>
                    <h2>Daftar Kategori</h2><span>{{ $categories->count() }} kategori</span>
                </div>
            </div>
            <div class="category-admin-table-wrap">
                <table id="category-table" class="category-admin-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Products Count</th>
                            <th>Total Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>
                                <div class="category-name-cell">
                                    <span class="category-name-icon">{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-name-link" title="Lihat produk {{ $category->name }}"><strong>{{ $category->name }}</strong></a>
                                </div>
                            </td>
                            <td><code class="category-slug">{{ $category->slug }}</code></td>
                            <td><span class="category-count-badge">{{ number_format($category->products_count) }} produk</span></td>
                            <td><strong>{{ number_format($category->products_sum_stock ?? 0) }}</strong></td>
                            <td>
                                <div class="category-admin-actions">
                                    <a href="{{ route('product-categories.edit', $category) }}" class="category-action edit" title="Edit kategori">✎ Edit</a>
                                    <form action="{{ route('product-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="category-action delete" title="Hapus kategori">⌫ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="category-admin-empty">Belum ada kategori produk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    @endpush

</x-app-layout>