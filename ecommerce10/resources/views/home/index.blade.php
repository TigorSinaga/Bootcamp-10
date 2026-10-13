@extends('template.layout')

@section('title', 'RuangBelanja — Temukan yang kamu suka')

@section('content')
@include('template.navbar')

<main class="page-content">

    <section class="hero" aria-label="Promo pilihan">
        <div class="hero-card">
            <div class="hero-copy">
                <span class="eyebrow">✦ Belanja lebih happy</span>
                <h1>Temukan hal kecil yang bikin <span>harimu spesial.</span></h1>
                <p>Dari kebutuhan sehari-hari sampai wishlist yang lama kamu simpan — semuanya ada di satu ruang.</p>
                <a class="primary-btn" href="#produk">Mulai jelajahi <span aria-hidden="true">→</span></a>
                <span class="hero-note">🚚 Gratis ongkir min. Rp100rb</span>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&amp;fit=crop&amp;w=1200&amp;q=85" alt="Suasana butik fashion dengan koleksi pilihan">
            </div>
            <div class="hero-sticker"><strong>70% OFF</strong>pilihan spesial hari ini</div>
        </div>
    </section>

    <section class="section" id="kategori">
        <div class="section-heading">
            <div>
                <div class="section-kicker">Cari yang kamu butuhkan</div>
                <h2>Belanja berdasarkan kategori</h2>
            </div>
            <a class="section-link" href="#produk">Lihat semua <span aria-hidden="true">→</span></a>
        </div>
        <div class="category-grid">
            @foreach ($categories as $category)
            <a class="category-card" href="#produk" data-category-link="{{ $category['slug'] }}">
                <span class="category-emoji {{ $category['style'] }}">{{ $category['icon'] }}</span>
                <strong>{{ $category['name'] }}</strong>
                <small>{{ $category['product_count'] }} produk</small>
            </a>
            @endforeach
        </div>
        <div class="benefit-row">
            <div class="benefit">
                <div class="benefit-icon">🚚</div>
                <div><strong>Gratis ongkir</strong><span>Untuk pesanan mulai Rp100.000</span></div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">🛡️</div>
                <div><strong>Belanja aman</strong><span>Transaksi nyaman dan terlindungi</span></div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">↩️</div>
                <div><strong>Garansi mudah</strong><span>Pengembalian tanpa ribet</span></div>
            </div>
        </div>
    </section>

    <section class="section" id="produk">
        <div class="deal-banner">
            <div class="deal-title"><span class="deal-bolt">⚡</span>
                <div><strong>Flash sale hari ini</strong><span>Jangan sampai kehabisan, harga spesial segera berakhir!</span></div>
            </div>
            <div class="countdown" aria-label="Waktu flash sale tersisa"><span class="time-box">05</span><span class="time-separator">:</span><span class="time-box">42</span><span class="time-separator">:</span><span class="time-box">18</span></div>
            <a class="section-link" href="/products">Lihat semua <span aria-hidden="true">→</span></a>
        </div>

        <div class="section-heading product-heading">
            <div>
                <div class="section-kicker">Dikurasi khusus untukmu</div>
                <h2>Produk pilihan minggu ini</h2>
            </div>
            <a class="section-link" href="/products">Jelajahi produk <span aria-hidden="true">→</span></a>
        </div>
        <div class="filter-row" role="group" aria-label="Filter produk">
            <button class="filter-btn active" type="button" data-filter="semua">Semua</button>
            @foreach ($filters as $category)
            <button class="filter-btn" type="button" data-filter="{{ $category['slug'] }}">{{ $category['name'] }}</button>
            @endforeach
        </div>
        <div class="product-grid" id="product-grid">
            @foreach ($products as $product)
            <article class="product-card" data-category="{{ $product['category'] }}" data-name="{{ mb_strtolower($product['name']) }}">
                <div class="product-photo">
                    <img src="{{ $product['image'] }}" alt="{{ $product['image_alt'] }}">
                    <span class="product-badge">{{ $product['badge'] }}</span>
                    <button class="favorite-btn" type="button" aria-label="Tambahkan {{ $product['name'] }} ke wishlist">♡</button>
                </div>
                <div class="product-info">
                    <span class="product-category">{{ $product['category_name'] }}</span>
                    <a class="product-name" href="/products">{{ $product['name'] }}</a>
                    <div class="rating">★ {{ $product['rating'] }} <span>({{ $product['review_count'] }} ulasan)</span></div>
                    <div class="price-row">
                        <span class="price">Rp{{ number_format($product['price'], 0, ',', '.') }}</span>
                        <span class="old-price">Rp{{ number_format($product['original_price'], 0, ',', '.') }}</span>
                    </div>
                    <button class="add-btn" type="button">+ Tambah ke keranjang</button>
                </div>
            </article>
            @endforeach
        </div>
        <div class="promo-strip" id="promo">
            <div class="promo-copy"><span>Spesial untuk member baru</span>
                <h2>Diskon 15% untuk belanja pertamamu</h2>
                <p>Kenalan dengan produk favoritmu, dengan harga yang lebih menyenangkan.</p>
            </div>
            <div class="promo-code"><small>Gunakan kode promo</small><strong>HALO15</strong></div>
        </div>
        <section class="newsletter" aria-label="Berlangganan newsletter">
            <div>
                <h2>Jadi yang pertama tahu ✨</h2>
                <p>Dapatkan update promo dan produk baru langsung di inbox kamu.</p>
            </div>
            <form class="newsletter-form" id="newsletter-form"><input type="email" placeholder="Alamat email kamu" aria-label="Alamat email" required><button class="primary-btn" type="submit">Ikut berlangganan</button></form>
        </section>
    </section>
</main>

@include('template.footer')
<div class="toast-message" id="toast-message" role="status" aria-live="polite"></div>

@push('scripts')
<script>
    const productCards = [...document.querySelectorAll('.product-card')];
    const filterButtons = [...document.querySelectorAll('.filter-btn')];
    const searchInput = document.querySelector('#product-search');
    const cartCount = document.querySelector('#cart-count');
    const toast = document.querySelector('#toast-message');
    let activeCategory = 'semua';
    let cartItems = 0;
    let toastTimeout;

    function updateProducts() {
        const query = searchInput.value.trim().toLocaleLowerCase('id');
        let visibleCount = 0;

        productCards.forEach((card) => {
            const matchesCategory = activeCategory === 'semua' || card.dataset.category === activeCategory;
            const matchesSearch = card.dataset.name.includes(query) || card.dataset.category.includes(query);
            card.hidden = !(matchesCategory && matchesSearch);
            if (!card.hidden) visibleCount += 1;
        });

        const existingEmptyState = document.querySelector('.empty-state');
        if (visibleCount === 0 && !existingEmptyState) {
            const emptyState = document.createElement('p');
            emptyState.className = 'empty-state';
            emptyState.textContent = 'Produk tidak ditemukan. Coba kata kunci atau kategori lain.';
            document.querySelector('#product-grid').append(emptyState);
        } else if (visibleCount > 0 && existingEmptyState) {
            existingEmptyState.remove();
        }
    }

    function selectCategory(category) {
        activeCategory = category;
        filterButtons.forEach((button) => {
            button.classList.toggle('active', button.dataset.filter === category);
        });
        updateProducts();
    }

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => selectCategory(button.dataset.filter));
    });

    document.querySelectorAll('[data-category-link]').forEach((link) => {
        link.addEventListener('click', () => {
            const category = link.dataset.categoryLink;
            if (filterButtons.some((button) => button.dataset.filter === category)) {
                selectCategory(category);
            } else {
                selectCategory('semua');
            }
        });
    });

    searchInput.addEventListener('input', updateProducts);

    document.querySelectorAll('.add-btn').forEach((button) => {
        button.addEventListener('click', () => {
            cartItems += 1;
            cartCount.textContent = cartItems;
            toast.textContent = 'Produk ditambahkan ke keranjang';
            toast.classList.add('show');
            window.clearTimeout(toastTimeout);
            toastTimeout = window.setTimeout(() => toast.classList.remove('show'), 2200);
        });
    });

    document.querySelectorAll('.favorite-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const isFavorite = button.textContent === '♥';
            button.textContent = isFavorite ? '♡' : '♥';
            button.style.color = isFavorite ? '' : '#fa6681';
            button.setAttribute('aria-pressed', String(!isFavorite));
        });
    });

    document.querySelector('#newsletter-form').addEventListener('submit', (event) => {
        event.preventDefault();
        toast.textContent = 'Terima kasih sudah berlangganan!';
        toast.classList.add('show');
        window.clearTimeout(toastTimeout);
        toastTimeout = window.setTimeout(() => toast.classList.remove('show'), 2200);
        event.currentTarget.reset();
    });
</script>
@endpush
@endsection