<header class="site-header">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="RuangBelanja beranda">
            <span class="brand-mark">r.</span>
            <span>ruang<span>belanja</span></span>
        </a>
        <form class="search-box" action="{{ route('search') }}" method="GET" role="search">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input
                id="product-search"
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk favoritmu..."
                autocomplete="off"
                aria-label="Cari produk">
            <button class="search-shortcut" type="submit">Cari</button>
        </form>
        <div class="header-actions">
            <a class="header-action" href="{{ route('home') }}#produk" aria-label="Wishlist">
                <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                <span>Wishlist</span>
            </a>
            <a class="header-action" href="{{ route('cart') }}" aria-label="Keranjang belanja">
                <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span>Keranjang</span>
                <span class="cart-count" id="cart-count">{{ $cartCount ?? 0 }}</span>
            </a>
            <details class="user-menu">
                <summary class="user-menu-btn">
                    <svg class="user-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="user-name">@auth{{ auth()->user()->name }}@else Akun @endauth</span>
                    <svg class="user-menu-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </summary>
                <ul class="user-menu-list">
                    @auth
                    <li class="user-menu-heading">{{ auth()->user()->name }}</li>
                    <li><a href="{{ route('profile.edit') }}">Profil</a></li>
                    <li><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                    @if (Auth::user()-> role === 'admin')
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>

                    @endif
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
    </div>
</header>