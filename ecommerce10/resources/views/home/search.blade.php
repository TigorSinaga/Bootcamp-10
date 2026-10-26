@extends('template.layout')

@section('title', 'RuangBelanja — Hasil pencarian')

@section('content')
@include('template.navbar')

<main class="page-content search-page">

    <section class="search-panel">
        <div class="search-panel-copy">
            <div class="section-kicker">Cari yang kamu butuhkan</div>
            <h1>Temukan produk favoritmu</h1>
            <p>Cari berdasarkan nama produk, deskripsi, atau kategori.</p>
        </div>
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <div class="search-field">
                <label for="search">Kata kunci</label>
                <input
                    id="search"
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Contoh: headphone, fashion...">
            </div>
            <div class="search-field">
                <label for="category">Kategori</label>
                <select id="category" name="category">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $item)
                    <option value="{{ $item->id }}" @selected((string) $category===(string) $item->id)>
                        {{ $item->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="search-form-actions">
                <button type="submit" class="primary-btn">Cari produk <span aria-hidden="true">→</span></button>
                <a href="{{ route('search') }}" class="search-reset">Reset filter</a>
            </div>
        </form>
    </section>

    <section class="section search-results" aria-labelledby="results-heading">
        <div class="section-heading">
            <div>
                <div class="section-kicker">Pilihan untukmu</div>
                <h2 id="results-heading">Hasil pencarian</h2>
                <p class="search-result-count">
                    Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                    dari {{ $products->total() }} produk
                    @if ($search !== '')
                    untuk <strong>“{{ $search }}”</strong>
                    @endif
                </p>
            </div>
        </div>

        @if ($products->isNotEmpty())
        <div class="product-grid">
            @foreach ($products as $product)
            <article class="product-card">
                <div class="product-photo">
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        loading="lazy">
                    <span class="product-badge">Tersedia</span>
                </div>
                <div class="product-info">
                    @if ($product->product_category)
                    <span class="product-category">{{ $product->product_category->name }}</span>
                    @endif
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="search-product-description">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</p>
                    <div class="price-row">
                        <span class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="search-stock">Stok tersedia <strong>{{ $product->stock }}</strong></div>
                </div>
            </article>
            @endforeach
        </div>

        <div class="search-pagination">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="empty-state">
            <div class="search-empty-icon" aria-hidden="true">⌕</div>
            <h3>Produk belum ditemukan</h3>
            <p>Coba kata kunci lain atau pilih kategori yang berbeda.</p>
            <a href="{{ route('search') }}" class="primary-btn">Lihat semua produk <span aria-hidden="true">→</span></a>
        </div>
        @endif
    </section>
</main>

@include('template.footer')
@endsection