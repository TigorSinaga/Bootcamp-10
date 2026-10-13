@extends('template.layout')

@section('title', 'Keranjang Belanja — RuangBelanja')

@section('content')
@include('template.navbar')

<main class="page-content cart-page" data-home-products-url="{{ route('home') }}#produk">
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
                        <article class="cart-item" data-item-id="{{ $item['id'] }}" data-price="{{ $item['price'] }}">
                            <a class="cart-item-image" href="{{ url('/products') }}" tabindex="-1" aria-hidden="true">
                                <img src="{{ $item['image'] }}" alt="">
                            </a>
                            <div class="cart-item-detail">
                                <span class="cart-item-category">{{ $item['category'] }}</span>
                                <a class="cart-item-name" href="{{ url('/products') }}">{{ $item['name'] }}</a>
                                <span class="cart-item-variant">{{ $item['variant'] }}</span>
                                <button class="cart-remove" type="button" aria-label="Hapus {{ $item['name'] }} dari keranjang">Hapus</button>
                            </div>
                            <div class="cart-item-actions">
                                <strong class="cart-item-price">Rp{{ number_format($item['price'], 0, ',', '.') }}</strong>
                                <div class="quantity-control" aria-label="Jumlah {{ $item['name'] }}">
                                    <button type="button" class="quantity-btn" data-quantity-change="-1" aria-label="Kurangi jumlah">−</button>
                                    <span class="quantity-value">{{ $item['quantity'] }}</span>
                                    <button type="button" class="quantity-btn" data-quantity-change="1" aria-label="Tambah jumlah">+</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <a class="cart-back-link" href="{{ route('home') }}#produk">← Tambah produk lainnya</a>
            </section>

            <aside class="cart-summary" aria-label="Ringkasan pesanan">
                <h2>Ringkasan pesanan</h2>
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
                <div class="shipping-progress">
                    <span class="shipping-icon">🚚</span>
                    <span>Yeay, kamu mendapatkan <strong>gratis ongkir!</strong></span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-total">
                    <span>Total pembayaran</span>
                    <strong id="cart-total" data-value="{{ $total }}">Rp{{ number_format($total, 0, ',', '.') }}</strong>
                </div>
                <a class="checkout-btn" href="{{ url('/checkout') }}">Lanjut ke pembayaran <span aria-hidden="true">→</span></a>
                <div class="secure-note"><span aria-hidden="true">🔒</span> Transaksimu aman dan terlindungi</div>
                <div class="payment-note"><span>💳</span><span>Transfer bank</span><span>📱</span><span>E-wallet</span><span>🏪</span><span>Minimarket</span></div>
            </aside>
        </div>
    @else
        <section class="cart-empty" id="cart-empty">
            <div class="empty-cart-icon">🛍️</div>
            <h2>Keranjangmu masih kosong</h2>
            <p>Yuk, temukan sesuatu yang kamu suka dan simpan di sini.</p>
            <a class="checkout-btn empty-cart-btn" href="{{ route('home') }}#produk">Mulai belanja <span aria-hidden="true">→</span></a>
        </section>
    @endif
</main>

@include('template.footer')
<div class="toast-message" id="toast-message" role="status" aria-live="polite"></div>

@push('scripts')
<script>
    const cartItemsList = document.querySelector('#cart-items-list');
    const summarySubtotal = document.querySelector('#cart-subtotal');
    const summaryTotal = document.querySelector('#cart-total');
    const shippingFee = document.querySelector('#shipping-fee');
    const cartCount = document.querySelector('#cart-count');
    const itemCount = document.querySelector('#cart-item-count');
    const summaryQuantity = document.querySelector('#summary-quantity');
    const toast = document.querySelector('#toast-message');
    let toastTimeout;

    function formatPrice(value) {
        return `Rp${new Intl.NumberFormat('id-ID').format(value)}`;
    }

    function updateCart() {
        const items = [...cartItemsList.querySelectorAll('.cart-item')];
        const quantity = items.reduce((total, item) => total + Number(item.querySelector('.quantity-value').textContent), 0);
        const subtotal = items.reduce((total, item) => total + Number(item.dataset.price) * Number(item.querySelector('.quantity-value').textContent), 0);
        const delivery = subtotal >= 100000 || subtotal === 0 ? 0 : 12000;

        summarySubtotal.textContent = formatPrice(subtotal);
        summarySubtotal.dataset.value = subtotal;
        summaryTotal.textContent = formatPrice(subtotal + delivery);
        summaryTotal.dataset.value = subtotal + delivery;
        shippingFee.textContent = delivery === 0 ? 'Gratis' : formatPrice(delivery);
        shippingFee.dataset.value = delivery;
        cartCount.textContent = quantity;
        itemCount.textContent = `${quantity} item`;
        summaryQuantity.textContent = `(${quantity} item)`;

        if (items.length === 0) {
            document.querySelector('#cart-content').remove();
            const emptyState = document.createElement('section');
            emptyState.className = 'cart-empty';
            emptyState.id = 'cart-empty';
            emptyState.innerHTML = `<div class="empty-cart-icon">🛍️</div><h2>Keranjangmu masih kosong</h2><p>Yuk, temukan sesuatu yang kamu suka dan simpan di sini.</p><a class="checkout-btn empty-cart-btn" href="${document.querySelector('.cart-page').dataset.homeProductsUrl}">Mulai belanja <span aria-hidden="true">→</span></a>`;
            document.querySelector('.cart-heading').after(emptyState);
        }
    }

    if (cartItemsList) {
        cartItemsList.addEventListener('click', (event) => {
            const button = event.target.closest('button');
            if (!button) return;

            const item = button.closest('.cart-item');
            if (button.classList.contains('cart-remove')) {
                item.remove();
                updateCart();
                return;
            }

            const change = Number(button.dataset.quantityChange);
            const quantity = item.querySelector('.quantity-value');
            const updatedQuantity = Number(quantity.textContent) + change;
            if (updatedQuantity < 1) return;

            quantity.textContent = updatedQuantity;
            updateCart();
        });
    }
</script>
@endpush
@endsection
