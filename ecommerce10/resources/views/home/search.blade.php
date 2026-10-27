@extends('template.layout')

@section('title', 'RuangBelanja — Hasil pencarian')

@section('content')
@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
@endphp
@include('template.navbar')

<main class="page-content search-page">
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

        <form action="{{ route('search') }}" method="GET" class="search-layout-form">
            @if ($search !== '')
            <input type="hidden" name="search" value="{{ $search }}">
            @endif
            <div class="search-layout">
                <aside class="search-sidebar" aria-labelledby="filters-heading">
                    <div class="search-sidebar-heading">
                        <h3 id="filters-heading">Filter</h3>
                        <a class="search-reset" href="{{ route('search', $search !== '' ? ['search' => $search] : []) }}">Reset</a>
                    </div>

                    <fieldset class="search-filter-group">
                        <h4>Kategori</h4>
                        @foreach ($categories as $item)
                        <label class="filter-option">
                            <input
                                type="checkbox"
                                name="category[]"
                                value="{{ $item->id }}"
                                @checked(in_array((string) $item->id, array_map('strval', $category), true))>
                            <span>{{ $item->name }}</span>
                        </label>
                        @endforeach
                    </fieldset>

                    <fieldset class="search-filter-group">
                        <h4>Rentang Harga</h4>
                        <label class="price-filter-field" for="min-price">
                            <span>Harga minimum</span>
                            <input id="min-price" type="number" name="min_price" min="0" step="1000" value="{{ $minPrice }}" placeholder="Rp minimum">
                        </label>
                        <label class="price-filter-field" for="max-price">
                            <span>Harga maksimum</span>
                            <input id="max-price" type="number" name="max_price" min="0" step="1000" value="{{ $maxPrice }}" placeholder="Rp maksimum">
                        </label>
                    </fieldset>

                    <button class="primary-btn search-apply-btn" type="submit">Terapkan filter</button>
                </aside>

                <div class="search-catalog">
                    <div class="search-toolbar">
                        <span class="search-toolbar-count">{{ $products->total() }} produk ditemukan</span>
                        <label class="sort-control" for="sort">
                            <span>Urutkan:</span>
                            <select id="sort" name="sort" onchange="this.form.requestSubmit()">
                                <option value="latest" @selected($sort==='latest' )>Terbaru</option>
                                <option value="price_desc" @selected($sort==='price_desc' )>Harga tertinggi</option>
                                <option value="price_asc" @selected($sort==='price_asc' )>Harga terendah</option>
                            </select>
                        </label>
                    </div>

                    @if ($products->isNotEmpty())
                    <div class="product-grid">
                        @foreach ($products as $product)
                        <article class="product-card">
                            <div class="product-photo">
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    data-fallback="{{ asset('images/product-placeholder.svg') }}"
                                    onerror="this.onerror=null;this.src=this.dataset.fallback"
                                    alt="{{ $product->name }}"
                                    loading="lazy">
                            </div>
                            <div class="product-info">
                                @if ($product->product_category)
                                <span class="product-category">{{ $product->product_category->name }}</span>
                                @endif
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-description">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</p>
                                <div class="price-row">
                                    <span class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <form action="{{ route('cart.items.store') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="add-btn" type="submit">+ Tambah ke keranjang</button>
                                </form>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="search-empty-icon" aria-hidden="true">⌕</div>
                        <h3>Produk belum ditemukan</h3>
                        <p>Coba ubah kategori atau rentang harga filter yang dipilih.</p>
                        <a href="{{ route('search') }}" class="primary-btn">Lihat semua produk <span aria-hidden="true">→</span></a>
                    </div>
                    @endif
                </div>
            </div>
        </form>

        @if ($products->isNotEmpty())
        <div class="search-pagination">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </section>
</main>

@include('template.footer')
<div class="toast-message" id="toast-message" role="status" aria-live="polite" data-message="{{ session('success') ?? session('error') }}"></div>

@push('scripts')
<script>
    const toast = document.querySelector('#toast-message');
    let toastTimeout;

    if (toast.dataset.message) {
        toast.textContent = toast.dataset.message;
        toast.classList.add('show');
        toastTimeout = window.setTimeout(() => toast.classList.remove('show'), 2600);
    }
</script>
@endpush
@endsection