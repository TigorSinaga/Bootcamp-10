<x-app-layout>
    <div class="dashboard-page">
        <section class="dashboard-welcome">
            <div>
                <h1>Dashboard <span class="dashboard-welcome-user">— Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋</span></h1>
                <p>Kelola produk, kategori, dan pesanan toko dari satu tempat.</p>
            </div>
        </section>

        <section class="dashboard-stats" aria-label="Ringkasan toko">
            <article class="dashboard-stat-card stat-purple">
                <div class="dashboard-stat-icon" aria-hidden="true">▦</div>
                <div><span>Total Produk</span><strong>{{ number_format($productCount) }}</strong></div>
            </article>
            <article class="dashboard-stat-card stat-orange">
                <div class="dashboard-stat-icon" aria-hidden="true">⌘</div>
                <div><span>Kategori</span><strong>{{ number_format($categoryCount) }}</strong></div>
            </article>
            <article class="dashboard-stat-card stat-blue">
                <div class="dashboard-stat-icon" aria-hidden="true">▤</div>
                <div><span>Total Pesanan</span><strong>{{ number_format($orderCount) }}</strong></div>
            </article>
            <article class="dashboard-stat-card stat-green">
                <div class="dashboard-stat-icon" aria-hidden="true">Rp</div>
                <div><span>Total Pendapatan</span><strong class="dashboard-money">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong></div>
            </article>
        </section>

        <section class="dashboard-grid">
            <article class="dashboard-panel dashboard-products-panel">
                <div class="dashboard-panel-heading">
                    <div><span class="dashboard-kicker">Inventaris</span>
                        <h2>Produk Terbaru</h2>
                    </div>
                    <a href="{{ route('products.index') }}">Lihat semua <span aria-hidden="true">→</span></a>
                </div>
                <div class="dashboard-table-wrap">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestProducts as $product)
                            <tr>
                                <td>
                                    <div class="dashboard-product-name">
                                        <div class="dashboard-product-thumb">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                                        <div><strong>{{ $product->name }}</strong><span>{{ $product->slug }}</span></div>
                                    </div>
                                </td>
                                <td>{{ $product->product_category?->name ?? 'Tanpa kategori' }}</td>
                                <td><span class="stock-badge {{ $product->stock <= 10 ? 'stock-low' : '' }}">{{ $product->stock }}</span></td>
                                <td><strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="dashboard-empty">Belum ada produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <aside class="dashboard-side">
                <article class="dashboard-panel dashboard-stock-alert">
                    <div class="dashboard-stock-icon" aria-hidden="true">!</div>
                    <div><span>Perhatian</span>
                        <h2>{{ $lowStockCount }} produk stok rendah</h2>
                        <p>Segera tambah stok agar produk tetap tersedia.</p>
                    </div>
                    <a href="{{ route('products.index') }}">Kelola stok</a>
                </article>

                <article class="dashboard-panel dashboard-shortcuts">
                    <div class="dashboard-panel-heading compact">
                        <div><span class="dashboard-kicker">Akses cepat</span>
                            <h2>Tindakan</h2>
                        </div>
                    </div>
                    <div class="dashboard-shortcut-list">
                        <a href="{{ route('products.create') }}"><span class="shortcut-icon purple">＋</span><span><strong>Produk baru</strong><small>Tambah produk ke toko</small></span><b>›</b></a>
                        <a href="{{ route('products.index') }}"><span class="shortcut-icon blue">▦</span><span><strong>Kelola inventaris</strong><small>Atur harga dan stok</small></span><b>›</b></a>
                    </div>
                </article>
            </aside>
        </section>

        <section class="dashboard-panel dashboard-orders-panel">
            <div class="dashboard-panel-heading">
                <div><span class="dashboard-kicker">Aktivitas</span>
                    <h2>Pesanan Terbaru</h2>
                </div>
            </div>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table dashboard-orders-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user?->name ?? 'Pelanggan' }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td><span class="order-status status-{{ strtolower($order->status ?? 'pending') }}">{{ ucfirst($order->status ?? 'pending') }}</span></td>
                            <td><strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="dashboard-empty">Belum ada pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>