<header class="site-header">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="RuangBelanja beranda">
            <span class="brand-mark">r.</span>
            <span>ruang<span>belanja</span></span>
        </a>
        <div class="header-actions">
            <a class="header-action" href="#produk" aria-label="Wishlist"><span class="action-icon">♡</span><span>Wishlist</span></a>
            <a class="header-action" href="{{ route('cart') }}" aria-label="Keranjang belanja"><span class="action-icon">🛍️</span><span>Keranjang</span><span class="cart-count" id="cart-count">{{ $cartCount ?? 0 }}</span></a>
            <details class="user-menu">
                <summary class="user-menu-btn">
                    <span class="user-icon" aria-hidden="true">👤</span>
                    <span class="user-name">@auth{{ auth()->user()->name }}@else Akun @endauth</span>
                    <span class="user-menu-chevron" aria-hidden="true">⌄</span>
                </summary>
                <ul class="user-menu-list">
                    @auth
                    <li class="user-menu-heading">{{ auth()->user()->name }}</li>
                    <li><a href="{{ route('profile.edit') }}">Profil</a></li>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Keluar</button>
                        </form>
                    </li>
                    @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </ul>
            </details>
        </div>
</header>