<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

    <div class="product-admin-page">
        <div class="db-header admin-index-header">
            <div class="db-header-left">
                <h1>Manajemen Pesanan</h1>
                <p>Pantau, cari, dan kelola seluruh transaksi pesanan pelanggan secara real-time.</p>
                <span class="db-header-badge">Live</span>
            </div>
        </div>

        <div class="db-stats admin-index-stats">
            <div class="db-stat"><div class="db-stat-icon icon-green">🛒</div><div class="db-stat-body"><div class="db-stat-label">Total Pesanan</div><div class="db-stat-value">{{ $statusCounts['all'] }}</div><div class="db-stat-sub">Semua transaksi</div></div></div>
            <div class="db-stat"><div class="db-stat-icon icon-orange">⏳</div><div class="db-stat-body"><div class="db-stat-label">Pending</div><div class="db-stat-value">{{ $statusCounts['pending'] }}</div><div class="db-stat-sub">Menunggu diproses</div></div></div>
            <div class="db-stat"><div class="db-stat-icon icon-blue">🔄</div><div class="db-stat-body"><div class="db-stat-label">Processing</div><div class="db-stat-value">{{ $statusCounts['processing'] }}</div><div class="db-stat-sub">Sedang disiapkan</div></div></div>
            <div class="db-stat"><div class="db-stat-icon icon-purple">✅</div><div class="db-stat-body"><div class="db-stat-label">Completed</div><div class="db-stat-value">{{ $statusCounts['completed'] }}</div><div class="db-stat-sub">Pesanan selesai</div></div></div>
        </div>

        @if (session('success'))
        <div class="product-admin-alert" style="background:#d1fae5;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-weight:500;">
            {{ session('success') }}
        </div>
        @endif

        <section class="product-admin-panel">
            <div class="product-admin-panel-head">
                <div>
                    <h2>Daftar Pesanan</h2>
                    <span>{{ $orders->total() }} pesanan ditemukan</span>
                </div>
            </div>

            <form action="{{ route('admin.orders.index') }}" method="GET" class="product-admin-filter-bar">
                <div class="product-admin-filter-field product-admin-search-field">
                    <label for="order-search">Cari pesanan</label>
                    <div class="product-admin-search-input">
                        <span aria-hidden="true">⌕</span>
                        <input id="order-search" type="search" name="search" placeholder="Nomor pesanan atau nama/email..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="product-admin-filter-field">
                    <label for="status-filter">Status</label>
                    <select id="status-filter" name="status">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="product-admin-filter-field">
                    <label for="sort-filter">Urutkan</label>
                    <select id="sort-filter" name="sort">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="highest" {{ request('sort') === 'highest' ? 'selected' : '' }}>Total tertinggi</option>
                        <option value="lowest" {{ request('sort') === 'lowest' ? 'selected' : '' }}>Total terendah</option>
                    </select>
                </div>

                <div class="product-admin-filter-actions">
                    @if (request()->filled('search') || (request()->filled('status') && request('status') !== 'all') || request()->filled('sort'))
                    <a href="{{ route('admin.orders.index') }}" class="product-admin-reset">Reset</a>
                    @endif
                    <button type="submit" class="product-admin-filter-button">Filter</button>
                </div>
            </form>

            <div class="product-admin-table-wrap">
                <table class="product-admin-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td><strong style="color:#111827;">#{{ $order->order_number }}</strong></td>
                            <td>
                                <div>
                                    <div style="font-weight:600;color:#111827;">{{ $order->customer_name ?: ($order->user?->name ?? 'Pelanggan') }}</div>
                                    <div style="font-size:12px;color:#6b7280;">{{ $order->customer_email ?: ($order->user?->email ?? '') }}</div>
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="order-status status-{{ strtolower($order->status ?? 'pending') }}" style="padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;display:inline-block;
                                    @if($order->status === 'completed') background:#d1fae5;color:#065f46;
                                    @elseif($order->status === 'processing') background:#dbeafe;color:#1e40af;
                                    @elseif($order->status === 'shipped') background:#e0e7ff;color:#3730a3;
                                    @elseif($order->status === 'cancelled') background:#fee2e2;color:#991b1b;
                                    @else background:#fef3c7;color:#92400e; @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span style="padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;display:inline-block;
                                    @if($order->payment_status === 'paid') background:#d1fae5;color:#065f46;
                                    @else background:#f3f4f6;color:#4b5563; @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td><strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                <div class="product-admin-actions">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="product-action view" title="Lihat detail pesanan">👁</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="product-admin-empty">Belum ada pesanan yang sesuai dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
            <div class="product-admin-pagination">{{ $orders->onEachSide(1)->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>
