<header class="site-header">
    <div class="topline">
        <div class="topline-inner">🎉 Payday Sale mulai sekarang — diskon hingga <strong>70%</strong> untuk pilihan favoritmu</div>
    </div>
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="RuangBelanja beranda">
            <span class="brand-mark">r.</span>
            <span>ruang<span>belanja</span></span>
        </a>
        <form class="search-box" role="search" action="{{ route('search') }}" method="GET">
            <span class="search-icon" aria-hidden="true">⌕</span>
            <input id="product-search" type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk, brand, atau kategori..." aria-label="Cari produk">
        </form>
        <div class="header-actions">
            <a class="header-action" href="#produk" aria-label="Wishlist"><span class="action-icon">♡</span><span>Wishlist</span></a>
            <a class="header-action" href="{{ route('cart') }}" aria-label="Keranjang belanja"><span class="action-icon">🛍️</span><span>Keranjang</span><span class="cart-count" id="cart-count">{{ $cartCount ?? 0 }}</span></a>
        </div>
    </div>
</header>