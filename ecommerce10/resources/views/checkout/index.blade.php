@extends('template.layout')

@section('title', 'Checkout — RuangBelanja')

@section('content')
@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
@include('template.navbar')

<main class="page-content cart-page checkout-page">
    <nav class="cart-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('cart') }}">Keranjang</a>
        <span aria-hidden="true">/</span>
        <span>Checkout</span>
    </nav>

    <div class="cart-heading">
        <div>
            <span class="cart-eyebrow">Satu langkah lagi ✨</span>
            <h1>Checkout</h1>
            <p>Lengkapi detail pengiriman dan periksa kembali pesananmu.</p>
        </div>
        <a class="cart-continue-link" href="{{ route('cart') }}">← Kembali ke keranjang</a>
    </div>

    @if (count($items))
    <div class="checkout-layout">
        <section class="checkout-form-panel" aria-label="Data pengiriman">
            <div class="checkout-section-heading">
                <span class="checkout-step">1</span>
                <div>
                    <h2>Informasi penerima</h2>
                    <p>Masukkan detail penerima pesanan.</p>
                </div>
            </div>

            <form class="checkout-form" id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                @if (session('error'))
                    <p class="checkout-form-error" role="alert">{{ session('error') }}</p>
                @endif
                @if ($errors->any())
                    <div class="checkout-form-error" role="alert">
                        <strong>Periksa kembali data checkout:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="checkout-field-grid">
                    <label class="checkout-field">
                        <span>Nama lengkap</span>
                        <input type="text" name="name" autocomplete="name" placeholder="Nama penerima" required value="{{ old('name', auth()->user()?->name) }}">
                    </label>
                    <label class="checkout-field">
                        <span>Nomor telepon</span>
                        <input type="tel" name="phone" autocomplete="tel" placeholder="08xxxxxxxxxx" required value="{{ old('phone') }}">
                    </label>
                    <label class="checkout-field checkout-field-full">
                        <span>Email</span>
                        <input type="email" name="email" autocomplete="email" placeholder="nama@email.com" required value="{{ old('email', auth()->user()?->email) }}">
                    </label>
                    <label class="checkout-field checkout-field-full">
                        <span>Alamat lengkap</span>
                        <textarea name="address" autocomplete="street-address" rows="4" placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota, dan kode pos" required>{{ old('address') }}</textarea>
                    </label>
                    <label class="checkout-field checkout-field-full">
                        <span>Catatan untuk kurir <small>(opsional)</small></span>
                        <textarea name="delivery_note" rows="3" placeholder="Contoh: rumah pagar hitam, titip ke satpam">{{ old('delivery_note') }}</textarea>
                    </label>
                </div>

                <div class="checkout-section-heading checkout-payment-heading">
                    <span class="checkout-step">2</span>
                    <div>
                        <h2>Metode pembayaran</h2>
                        <p>Pilih metode yang akan digunakan.</p>
                    </div>
                </div>

                <fieldset class="payment-options">
                    <legend class="visually-hidden">Pilih metode pembayaran</legend>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank_transfer" checked>
                        <span class="payment-option-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22h18"/><path d="M6 18v-7"/><path d="M10 18v-7"/><path d="M14 18v-7"/><path d="M18 18v-7"/><path d="m2 11 10-9 10 9"/><path d="M12 22v-4"/></svg>
                        </span>
                        <span><strong>Transfer bank</strong><small>Virtual account atau transfer manual</small></span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="ewallet">
                        <span class="payment-option-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                        </span>
                        <span><strong>E-wallet</strong><small>Pembayaran melalui dompet digital</small></span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="minimarket">
                        <span class="payment-option-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 5 4 4"/><path d="M13 7 8.7 2.7a2.41 2.41 0 0 0-3.4 0L2.7 5.3a2.41 2.41 0 0 0 0 3.4L7 13"/><path d="m8 6 2-2"/><path d="m2 22 7-7"/><path d="M11 11 7 15"/><path d="m11.5 11.5 2.5 2.5"/><path d="m15.6 15.6 4.4 4.4a2.41 2.41 0 0 0 3.4-3.4l-4.4-4.4"/></svg>
                        </span>
                        <span><strong>Minimarket</strong><small>Bayar di gerai minimarket terdekat</small></span>
                    </label>
                </fieldset>
            </form>
        </section>

        <aside class="cart-summary checkout-summary" aria-label="Ringkasan checkout">
            <h2>Ringkasan pesanan</h2>
            <div class="summary-items">
                @foreach ($items as $item)
                @php($product = $item['product'])
                <div class="summary-item">
                    <span class="summary-item-detail">
                        <strong>{{ $product->name }}</strong>
                        <small>{{ $item['quantity'] }} × Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                    </span>
                    <strong class="summary-item-total">Rp{{ number_format($product->price * $item['quantity'], 0, ',', '.') }}</strong>
                </div>
                @endforeach
            </div>
            <div class="summary-line">
                <span>Subtotal <span class="summary-muted">({{ $cartCount }} item)</span></span>
                <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-line">
                <span>Ongkos kirim</span>
                <strong class="free-shipping">{{ $shippingFee === 0 ? 'Gratis' : 'Rp'.number_format($shippingFee, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-total">
                <span>Total pembayaran</span>
                <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
            </div>
            <button class="checkout-btn checkout-submit" type="submit" form="checkout-form">
                Konfirmasi pesanan <span aria-hidden="true">→</span>
            </button>
            <div class="secure-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Transaksimu aman dan terlindungi
            </div>
        </aside>
    </div>
    @else
    <section class="cart-empty">
        <div class="empty-cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <h2>Keranjangmu masih kosong</h2>
        <p>Tambahkan produk terlebih dahulu sebelum melanjutkan checkout.</p>
        <a class="checkout-btn empty-cart-btn" href="{{ route('home') }}#produk">Mulai belanja <span aria-hidden="true">→</span></a>
    </section>
    @endif
</main>

@include('template.footer')
@endsection
