<x-app-layout>

    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

    <div class="db-page">

        {{-- ── Header ─────────────────────────────────────── --}}
        <div class="db-header">
            <div class="db-header-left">
                <h1>Dashboard Admin</h1>
                <p>Selamat datang kembali, <strong>{{ explode(' ', auth()->user()->name)[0] }}</strong> 👋 — berikut ringkasan toko hari ini.</p>
                <span class="db-header-badge">Live</span>
            </div>
            <div class="db-date">
                <span id="live-clock">Memuat...</span>
            </div>
        </div>

        {{-- ── Stat Cards ──────────────────────────────────── --}}
        <div class="db-stats">

            <div class="db-stat">
                <div class="db-stat-icon icon-purple">📦</div>
                <div class="db-stat-body">
                    <div class="db-stat-label">Total Produk</div>
                    <div class="db-stat-value count-up" data-target="{{ $productCount }}">0</div>
                    <div class="db-stat-sub">{{ $lowStockCount }} stok rendah</div>
                </div>
                @if($lowStockCount > 0)
                <span class="db-stat-trend trend-warn">⚠ Low</span>
                @else
                <span class="db-stat-trend trend-up">✓ OK</span>
                @endif
            </div>

            <div class="db-stat">
                <div class="db-stat-icon icon-orange">🏷️</div>
                <div class="db-stat-body">
                    <div class="db-stat-label">Kategori</div>
                    <div class="db-stat-value count-up" data-target="{{ $categoryCount }}">0</div>
                    <div class="db-stat-sub">Kelompok produk</div>
                </div>
            </div>

            <div class="db-stat">
                <div class="db-stat-icon icon-blue">👥</div>
                <div class="db-stat-body">
                    <div class="db-stat-label">Pengguna</div>
                    <div class="db-stat-value count-up" data-target="{{ $userCount }}">0</div>
                    <div class="db-stat-sub">User terdaftar</div>
                </div>
                <span class="db-stat-trend trend-up">+Baru</span>
            </div>

            <div class="db-stat">
                <div class="db-stat-icon icon-green">🛒</div>
                <div class="db-stat-body">
                    <div class="db-stat-label">Total Pesanan</div>
                    <div class="db-stat-value count-up" data-target="{{ $orderCount }}">0</div>
                    <div class="db-stat-sub">Semua transaksi</div>
                </div>
            </div>

            <div class="db-stat">
                <div class="db-stat-icon icon-pink">👆</div>
                <div class="db-stat-body">
                    <div class="db-stat-label">Product Clicks</div>
                    <div class="db-stat-value count-up" data-target="{{ $productClicks }}">0</div>
                    <div class="db-stat-sub">Klik produk bulan ini</div>
                </div>
                <span class="db-stat-trend trend-up">↑ 12%</span>
            </div>

            <div class="db-stat">
                <div class="db-stat-icon icon-green">💰</div>
                <div class="db-stat-body">
                    <div class="db-stat-label">Revenue</div>
                    <div class="db-stat-value" style="font-size:18px;letter-spacing:-0.5px;">
                        Rp<span class="count-up" data-target="{{ (int) $totalRevenue }}">0</span>
                    </div>
                    <div class="db-stat-sub">Tidak termasuk yang dibatalkan</div>
                </div>
                <span class="db-stat-trend trend-up">↑ 8%</span>
            </div>

        </div>

        {{-- ── Charts ───────────────────────────────────────── --}}
        <div class="db-charts">

            {{-- Orders per Month --}}
            <div class="db-panel">
                <div class="db-panel-head">
                    <div>
                        <h2 class="db-panel-title">Pesanan per Bulan</h2>
                        <p class="db-panel-subtitle">6 bulan terakhir</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <div class="live-dot"></div>
                        <span style="font-size:11px;color:var(--db-muted);">Live</span>
                    </div>
                </div>
                <div class="db-panel-body chart-wrap">
                    <canvas id="ordersChart"
                        data-labels="{{ json_encode($monthLabels) }}"
                        data-values="{{ json_encode($monthData) }}">
                    </canvas>
                </div>
            </div>

            {{-- Order Status --}}
            <div class="db-panel">
                <div class="db-panel-head">
                    <div>
                        <h2 class="db-panel-title">Status Pesanan</h2>
                        <p class="db-panel-subtitle">Distribusi status</p>
                    </div>
                </div>
                <div class="db-panel-body chart-wrap" style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                    <canvas id="statusChart"
                        style="max-height:220px;max-width:220px;"
                        data-labels="{{ json_encode($statusLabels) }}"
                        data-values="{{ json_encode($statusData) }}">
                    </canvas>
                    <div id="status-legend" class="db-status-legend"></div>
                </div>
            </div>

        </div>

        {{-- ── Bottom: Table + Actions ──────────────────────── --}}
        <div class="db-bottom">

            {{-- Latest Products --}}
            <div class="db-panel">
                <div class="db-panel-head">
                    <div>
                        <h2 class="db-panel-title">Produk Terbaru</h2>
                        <p class="db-panel-subtitle">5 produk paling baru ditambahkan</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="db-panel-action">Lihat semua →</a>
                </div>
                <div class="db-table-wrap">
                    <table class="db-table">
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
                                    <div class="db-product-cell">
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="db-product-thumb" alt="{{ $product->name }}">
                                        @else
                                        <div class="db-product-avatar">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                                        @endif
                                        <div>
                                            <div class="db-product-name">{{ Str::limit($product->name, 28) }}</div>
                                            <div class="db-product-slug">{{ $product->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->product_category?->name ?? '—' }}</td>
                                <td>
                                    <span class="stock-badge {{ $product->stock <= 10 ? 'stock-low' : '' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td><strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="db-empty">
                                    Belum ada produk. <a href="{{ route('products.create') }}">Tambah sekarang →</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="db-panel">
                <div class="db-panel-head">
                    <div>
                        <h2 class="db-panel-title">Aksi Cepat</h2>
                        <p class="db-panel-subtitle">Navigasi ke halaman utama</p>
                    </div>
                </div>
                <div class="db-actions">
                    <a href="{{ route('products.create') }}" class="db-action-btn action-purple">
                        <div class="db-action-icon icon-purple">➕</div>
                        <div>
                            <div class="db-action-label">Tambah Produk</div>
                            <div class="db-action-desc">Daftarkan produk baru ke toko</div>
                        </div>
                        <span class="db-action-arrow">›</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="db-action-btn action-blue">
                        <div class="db-action-icon icon-blue">📋</div>
                        <div>
                            <div class="db-action-label">Kelola Inventaris</div>
                            <div class="db-action-desc">Atur harga, stok, dan detail produk</div>
                        </div>
                        <span class="db-action-arrow">›</span>
                    </a>
                    <a href="{{ route('product-categories.index') }}" class="db-action-btn action-orange">
                        <div class="db-action-icon icon-orange">🏷️</div>
                        <div>
                            <div class="db-action-label">Kelola Kategori</div>
                            <div class="db-action-desc">Organisasi produk berdasarkan kategori</div>
                        </div>
                        <span class="db-action-arrow">›</span>
                    </a>
                    <a href="{{ route('home') }}" class="db-action-btn action-green">
                        <div class="db-action-icon icon-green">🛍️</div>
                        <div>
                            <div class="db-action-label">Lihat Toko</div>
                            <div class="db-action-desc">Tampilan storefront publik</div>
                        </div>
                        <span class="db-action-arrow">›</span>
                    </a>
                </div>
            </div>

        </div>

        {{-- ── Latest Orders ─────────────────────────────────── --}}
        <div class="db-panel">
            <div class="db-panel-head">
                <div>
                    <h2 class="db-panel-title">Pesanan Terbaru</h2>
                    <p class="db-panel-subtitle">5 transaksi terakhir masuk</p>
                </div>
            </div>
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user?->name ?? 'Pelanggan' }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="order-status status-{{ strtolower($order->status ?? 'pending') }}">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <span class="payment-status pay-{{ strtolower($order->payment_status ?? 'unpaid') }}">
                                    {{ ucfirst($order->payment_status ?? 'unpaid') }}
                                </span>
                            </td>
                            <td><strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="db-empty">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-app-layout>