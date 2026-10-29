<x-app-layout>
    <div class="category-admin-page">
        <div class="category-admin-heading">
            <div>
                <span class="dashboard-kicker">MANAJEMEN TOKO</span>
                <h1>Product Categories</h1>
                <p>Kelola kategori untuk mengelompokkan produk toko.</p>
            </div>
            <button type="button" class="category-admin-add" data-bs-toggle="modal" data-category-modal-open data-bs-target="#createCategoryModal">＋ Tambahkan Kategori Baru</button>
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
                                    <button type="button" class="category-action edit" data-category-edit-modal
                                        data-category-id="{{ $category->id }}" data-category-name="{{ e($category->name) }}"
                                        data-category-update-url="{{ route('product-categories.update', $category) }}">✎ Edit</button>
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
    <div class="modal fade" id="createCategoryModal" tabindex="-1" data-validation-error="{{ $errors->has('name') ? 'true' : 'false' }}" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content category-modal-content">
                <form action="{{ route('product-categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header category-modal-header">
                        <div><span class="dashboard-kicker">KATEGORI BARU</span>
                            <h2 id="createCategoryModalLabel" class="modal-title">Tambahkan kategori</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body category-modal-body">
                        <label for="category-name">Nama kategori</label>
                        <input id="category-name" class="category-modal-input" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik" required autofocus minlength="3">
                        @error('name')<span class="category-form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="modal-footer category-modal-footer">
                        <button type="button" class="category-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="category-admin-add">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content category-modal-content">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header category-modal-header">
                        <div><span class="dashboard-kicker">EDIT KATEGORI</span>
                            <h2 id="editCategoryModalLabel" class="modal-title">Perbarui kategori</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body category-modal-body">
                        <label for="edit-category-name">Nama kategori</label>
                        <input id="edit-category-name" class="category-modal-input" type="text" name="name" required minlength="3">
                    </div>
                    <div class="modal-footer category-modal-footer">
                        <button type="button" class="category-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="category-admin-add">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    @endpush
</x-app-layout>