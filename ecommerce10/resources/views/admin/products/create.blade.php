<x-app-layout>
    <div class="product-form-page">
        <div class="product-admin-heading">
            <div><span class="dashboard-kicker">MANAJEMEN TOKO</span>
                <h1>Tambah Produk</h1>
                <p>Lengkapi informasi dan potong gambar produk.</p>
            </div>
            <a href="{{ route('products.index') }}" class="product-form-back">← Kembali</a>
        </div>
        <form class="product-form-panel" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="cropped-image" name="cropped_image">
            <div class="product-form-grid">
                <div class="product-form-fields">
                    <div class="product-form-field"><label for="product-name">Nama Produk <span>*</span></label><input id="product-name" name="name" value="{{ old('name') }}" required maxlength="255"><small>Contoh: Laptop Gaming 14 Inch</small>@error('name')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    <div class="product-form-field"><label for="product-category">Kategori <span>*</span></label><select id="product-category" name="product_category_id" required>
                            <option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('product_category_id')==$category->id)>{{ $category->name }}</option>@endforeach
                        </select>@error('product_category_id')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    <div class="product-form-field"><label for="product-description">Deskripsi <span>*</span></label><textarea id="product-description" name="description" rows="5" required maxlength="5000">{{ old('description') }}</textarea>@error('description')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    <div class="product-form-row">
                        <div class="product-form-field"><label for="product-price">Harga <span>*</span></label>
                            <div class="product-input-prefix"><span>Rp</span><input id="product-price" name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required></div>@error('price')<p class="product-form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="product-form-field"><label for="product-stock">Stock <span>*</span></label><input id="product-stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', 0) }}" required>@error('stock')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    </div>
                    <label class="product-active-toggle"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', '1' ))><span>Produk aktif dan dapat dilihat pelanggan</span></label>
                </div>
                <div class="product-image-editor">
                    <label for="product-image-input">Gambar Produk <span>*</span></label>
                    <div id="product-cropper" class="product-cropper" hidden></div>
                    <div id="product-image-preview" class="product-image-preview"><span>⌗</span>
                        <p>Pilih gambar untuk dipotong</p><small>JPG, PNG, WEBP · Maks. 5 MB</small>
                    </div>
                    <label class="product-image-upload" for="product-image-input">Pilih Gambar</label>
                    <input id="product-image-input" type="file" accept="image/jpeg,image/png,image/webp" required>
                    @error('cropped_image')<p class="product-form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="product-form-actions"><a href="{{ route('products.index') }}" class="product-form-cancel">Batal</a><button type="submit" class="product-admin-add">Simpan Produk</button></div>
        </form>
    </div>
</x-app-layout>