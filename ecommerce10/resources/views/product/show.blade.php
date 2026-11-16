@extends('template.layout')

@section('title', $product->name . ' — RuangBelanja')

@section('content')
@include('template.navbar')

<main class="page-content">
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                @if($product->product_category)
                <li class="breadcrumb-item"><a href="{{ route('search', ['category' => [$product->product_category_id]]) }}">{{ $product->product_category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="product-detail-img-wrapper rounded-4 overflow-hidden border bg-white shadow-sm">
                    <img src="{{ asset('storage/' . $product->image) }}" data-fallback="{{ asset('images/product-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.dataset.fallback" alt="{{ $product->name }}" class="w-100 object-fit-cover" style="max-height: 500px;">
                </div>
            </div>
            <div class="col-lg-6">
                <span class="badge bg-secondary-subtle text-secondary mb-2 px-3 py-2 rounded-pill">{{ $product->product_category?->name ?? 'Umum' }}</span>
                <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
                <div class="fs-3 fw-bold text-primary mb-4">Rp{{ number_format($product->price, 0, ',', '.') }}</div>

                <div class="mb-4">
                    <h5 class="fw-semibold mb-2">Deskripsi Produk</h5>
                    <p class="text-muted" style="white-space: pre-line;">{{ $product->description }}</p>
                </div>

                <div class="mb-4">
                    <span class="text-muted">Stok tersedia: </span>
                    <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock > 0 ? $product->stock . ' unit' : 'Habis' }}
                    </strong>
                </div>

                @if($product->stock > 0)
                <form action="{{ route('cart.items.store') }}" method="POST" class="d-flex align-items-center gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-semibold shadow-sm">
                        + Tambah ke Keranjang
                    </button>
                </form>
                @else
                <button type="button" class="btn btn-secondary btn-lg px-5 py-3 rounded-pill fw-semibold" disabled>
                    Stok Habis
                </button>
                @endif
            </div>
        </div>
    </div>
</main>

@include('template.footer')
<div class="toast-message" id="toast-message" role="status" aria-live="polite" data-message="{{ session('success') ?? session('error') }}"></div>
@endsection
