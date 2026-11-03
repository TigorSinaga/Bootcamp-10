@php
$selectedCategory = old('product_category_id', $product?->product_category_id);
$currentImageUrl = $product?->image ? asset('storage/' . $product->image) : null;
@endphp

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if ($method === 'PUT')
        @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product?->name) }}" required>
        </div>

        <div class="form-group">
            <label for="product_category_id">Kategori</label>
            <select name="product_category_id" id="product_category_id" required>
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" rows="5" required>{{ old('description', $product?->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Harga</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product?->price) }}" min="0" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product?->stock ?? 0) }}" min="0" step="1" required>
        </div>

        <div class="form-group">
            <label for="is_active">Aktif</label>
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}>
        </div>

        <div class="form-group">
            <label for="image">Gambar Produk</label>
            @if ($product && $product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 200px; display: block; margin-bottom: 10px;">
            @endif