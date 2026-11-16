@extends('template.layout')

@section('title', 'Keranjang Belanja — RuangBelanja')

@section('content')
@include('template.navbar')

<main class="page-content cart-page">
    <nav class="cart-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span aria-hidden="true">/</span>
        <span>Keranjang</span>
    </nav>

    <div class="cart-heading">
        <div>
            <span class="cart-eyebrow">Pilihan yang tepat ✨</span>
            <h1>Keranjang belanja</h1>
            <p>Periksa lagi pilihanmu sebelum lanjut ke pembayaran.</p>
        </div>
        <a class="cart-continue-link" href="{{ route('home') }}#produk">← Lanjut belanja</a>
    </div>

    @if (count($items))
    <div class="cart-layout" id="cart-content">
        <section class="cart-items-panel" aria-label="Item di keranjang">
            <div class="cart-items-header">
                <strong>Produk pilihanmu</strong>
                <span id="cart-item-count">{{ $cartCount }} item</span>
            </div>

            <div class="cart-items-list" id="cart-items-list">
                @foreach ($items as $item)
                @php($product = $item['product'])
                <article class="cart-item" data-product-id="{{ $product->id }}" data-stock="{{ $product->stock }}">
                    <a class="cart-item-image" href="{{ route('search', ['search' => $product->name]) }}" tabindex="-1" aria-hidden="true">
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            data-fallback="{{ asset('images/product-placeholder.svg') }}"
                            onerror="this.onerror=null;this.src=this.dataset.fallback"
                            alt="">
                    </a>
                    <div class="cart-item-detail">
                        <span class="cart-item-category">{{ $product->product_category?->name ?? 'Produk' }}</span>
                        <a class="cart-item-name" href="{{ route('search', ['search' => $product->name]) }}">{{ $product->name }}</a>
                        <span class="cart-item-variant">{{ \Illuminate\Support\Str::limit($product->description, 70) }}</span>
                        <form action="{{ route('cart.items.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="cart-remove" type="submit" aria-label="Hapus {{ $product->name }} dari keranjang">Hapus</button>
                        </form>
                    </div>
                    <div class="cart-item-actions">
                        <strong class="cart-item-price">Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                        <div class="quantity-control" aria-label="Jumlah {{ $product->name }}">
                            <form action="{{ route('cart.items.update', $product) }}" method="POST" class="cart-quantity-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                <button class="quantity-btn" type="submit" aria-label="Kurangi jumlah" data-quantity-step="-1" @disabled($item['quantity'] <=1)>−</button>
                            </form>
                            <span class="quantity-value" data-quantity-value>{{ $item['quantity'] }}</span>
                            <form action="{{ route('cart.items.update', $product) }}" method="POST" class="cart-quantity-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                <button class="quantity-btn" type="submit" aria-label="Tambah jumlah" data-quantity-step="1" @disabled($item['quantity']>= $product->stock)>+</button>
                            </form>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <a class="cart-back-link" href="{{ route('home') }}#produk">← Tambah produk lainnya</a>
        </section>

        <aside class="cart-summary" aria-label="Ringkasan pesanan">
            <h2>Ringkasan pesanan</h2>
            <div class="summary-items" id="summary-items" aria-label="Rincian produk">
                @foreach ($items as $item)
                @php($product = $item['product'])
                <div class="summary-item" data-summary-product-id="{{ $product->id }}">
                    <span class="summary-item-detail">
                        <strong>{{ $product->name }}</strong>
                        <small><span data-summary-quantity>{{ $item['quantity'] }}</span> × Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                    </span>
                    <strong class="summary-item-total" data-summary-total>Rp{{ number_format($product->price * $item['quantity'], 0, ',', '.') }}</strong>
                </div>
                @endforeach
            </div>
            <div class="summary-line">
                <span>Subtotal <span class="summary-muted" id="summary-quantity">({{ $cartCount }} item)</span></span>
                <strong id="cart-subtotal" data-value="{{ $subtotal }}">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-line">
                <span>Ongkos kirim</span>
                <strong class="free-shipping" id="shipping-fee" data-value="{{ $shippingFee }}">
                    {{ $shippingFee === 0 ? 'Gratis' : 'Rp'.number_format($shippingFee, 0, ',', '.') }}
                </strong>
            </div>
            <div class="shipping-progress" id="shipping-progress">
                <div class="shipping-bar-track mb-2">
                    <div class="shipping-bar-fill {{ $shippingFee === 0 ? 'is-free' : '' }}" id="shipping-bar-fill" style="width: {{ min(100, max(5, round(($subtotal / 100000) * 100))) }}%;"></div>
                </div>
                <span class="shipping-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10"/><path d="M15 18H9"/><path d="M18 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 16.52 8H14"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                </span>
                @if ($shippingFee === 0)
                <span data-shipping-message>Yeay, kamu mendapatkan <strong>gratis ongkir!</strong></span>
                @else
                <span data-shipping-message>Belanja lagi <strong>Rp{{ number_format(max(0, 100000 - $subtotal), 0, ',', '.') }}</strong> untuk gratis ongkir.</span>
                @endif
            </div>
            <div class="summary-divider"></div>
            <div class="summary-total">
                <span>Total pembayaran</span>
                <strong id="cart-total" data-value="{{ $total }}">Rp{{ number_format($total, 0, ',', '.') }}</strong>
            </div>
            <a class="checkout-btn" href="{{ route('checkout') }}">Lanjut ke pembayaran <span aria-hidden="true">→</span></a>
            <div class="secure-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Transaksimu aman dan terlindungi
            </div>
        </aside>
    </div>
    @else
    <section class="cart-empty" id="cart-empty">
        <div class="empty-cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <h2>Keranjangmu masih kosong</h2>
        <p>Yuk, temukan sesuatu yang kamu suka dan simpan di sini.</p>
        <a class="checkout-btn empty-cart-btn" href="{{ route('home') }}#produk">Mulai belanja <span aria-hidden="true">→</span></a>
    </section>
    @endif
</main>

@include('template.footer')
<div class="toast-message" id="toast-message" role="status" aria-live="polite" data-message="{{ session('error') }}"></div>

@push('scripts')
@endpush
@endsection
