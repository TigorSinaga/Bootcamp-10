<footer>
    <div class="footer-inner">
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <a class="brand" href="{{ route('home') }}" style="font-size: 1.1rem; gap: 6px; margin-bottom: 4px;">
                <span class="brand-mark" style="width: 26px; height: 26px; border-radius: 8px; font-size: 0.8rem;">r.</span>
                <span style="letter-spacing: -0.5px;">ruang<span>belanja</span></span>
            </a>
            <span class="footer-note">© 2026 ruangbelanja. Temukan hal kecil yang bikin harimu spesial.</span>
        </div>
        <nav class="footer-links" aria-label="Tautan footer">
            <a href="{{ route('search') }}">Produk</a>
            <a href="{{ route('cart') }}">Keranjang</a>
            <a href="{{ route('orders.index') }}">Pesanan Saya</a>
            <a href="{{ route('home') }}#promo">Promo</a>
        </nav>
    </div>
</footer>
