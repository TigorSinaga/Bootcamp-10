<x-app-layout>
    <div class="category-admin-page">
        <div class="category-admin-heading">
            <div><span class="dashboard-kicker">MANAJEMEN TOKO</span><h1>Tambah Kategori</h1><p>Buat kategori baru untuk produk.</p></div>
            <a href="{{ route('product-categories.index') }}" class="category-admin-back">← Kembali</a>
        </div>
        <section class="category-form-panel">
            <form action="{{ route('product-categories.store') }}" method="POST">
                @csrf
                <label for="category-name">Nama kategori</label>
                <input id="category-name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik" required>
                @error('name')<span class="category-form-error">{{ $message }}</span>@enderror
                <button type="submit" class="category-admin-add">Simpan Kategori</button>
            </form>
        </section>
    </div>
</x-app-layout>
