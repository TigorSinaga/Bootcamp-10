<x-app-layout>
    <div class="category-admin-page">
        <div class="category-admin-heading">
            <div><span class="dashboard-kicker">MANAJEMEN TOKO</span><h1>Edit Kategori</h1><p>Perbarui nama kategori produk.</p></div>
            <a href="{{ route('product-categories.index') }}" class="category-admin-back">← Kembali</a>
        </div>
        <section class="category-form-panel">
            <form action="{{ route('product-categories.update', $productCategory) }}" method="POST">
                @csrf
                @method('PUT')
                <label for="category-name">Nama kategori</label>
                <input id="category-name" type="text" name="name" value="{{ old('name', $productCategory->name) }}" required>
                @error('name')<span class="category-form-error">{{ $message }}</span>@enderror
                <button type="submit" class="category-admin-add">Simpan Perubahan</button>
            </form>
        </section>
    </div>
</x-app-layout>
