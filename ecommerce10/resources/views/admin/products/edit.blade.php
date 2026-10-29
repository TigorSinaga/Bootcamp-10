<x-app-layout>
    <div class="product-form-page">
        <div class="product-admin-heading">
            <div><span class="dashboard-kicker">MANAJEMEN TOKO</span><h1>Edit Produk</h1><p>Perbarui informasi produk.</p></div>
            <a href="{{ route('products.index') }}" class="product-form-back">← Kembali</a>
        </div>
        <form class="product-form-panel" action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="cropped-image" name="cropped_image">
            <div class="product-form-grid">
                <div class="product-form-fields">
                    <div class="product-form-field"><label for="product-name">Nama Produk <span>*</span></label><input id="product-name" name="name" value="{{ old('name', $product->name) }}" required maxlength="255">@error('name')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    <div class="product-form-field"><label for="product-category">Kategori <span>*</span></label><select id="product-category" name="product_category_id" required><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('product_category_id', $product->product_category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('product_category_id')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    <div class="product-form-field"><label for="product-description">Deskripsi <span>*</span></label><textarea id="product-description" name="description" rows="5" required maxlength="5000">{{ old('description', $product->description) }}</textarea>@error('description')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    <div class="product-form-row">
                        <div class="product-form-field"><label for="product-price">Harga <span>*</span></label><div class="product-input-prefix"><span>Rp</span><input id="product-price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price) }}" required></div>@error('price')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                        <div class="product-form-field"><label for="product-stock">Stock <span>*</span></label><input id="product-stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $product->stock) }}" required>@error('stock')<p class="product-form-error">{{ $message }}</p>@enderror</div>
                    </div>
                    <label class="product-active-toggle"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))><span>Produk aktif dan dapat dilihat pelanggan</span></label>
                </div>
                <div class="product-image-editor">
                    <label>Gambar Produk</label>
                    @if($product->image)
                        <div id="edit-product-image-preview" class="product-image-preview edit-preview"><img src="{{ asset('storage/' . $product->image) }}?v={{ $product->updated_at?->timestamp ?? time() }}" data-fallback="" alt="{{ $product->name }}" onerror="this.onerror=null;this.closest('.product-image-preview').innerHTML='<span>⌗</span><p>Pratinjau gambar tidak tersedia</p>';">
                        </div>
                    @else
                        <div id="edit-product-image-preview" class="product-image-preview edit-preview"><span>⌗</span><p>Belum ada gambar produk</p></div>
                    @endif
                    <div id="edit-product-cropper" class="product-cropper" hidden></div>
                    <label for="edit-product-image-input" class="product-image-upload">Ganti Gambar</label>
                    <input id="edit-product-image-input" type="file" accept="image/jpeg,image/png,image/webp" class="product-edit-image-input">
                    <small class="product-image-note">Kosongkan jika tidak ingin mengganti gambar. Maks. 5 MB.</small>
                    @error('cropped_image')<p class="product-form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="product-form-actions"><a href="{{ route('products.index') }}" class="product-form-cancel">Batal</a><button type="submit" class="product-admin-add">Simpan Perubahan</button></div>
        </form>
    </div>
</x-app-layout>
