<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

    <div class="product-admin-page" style="max-width:1000px;margin:0 auto;">
        <div style="margin-bottom:20px;">
            <a href="{{ route('admin.orders.index') }}" style="color:#4b5563;text-decoration:none;font-weight:500;">← Kembali ke Daftar Pesanan</a>
        </div>

        @if (session('success'))
        <div class="product-admin-alert" style="background:#d1fae5;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-weight:500;">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-weight:500;">
            {{ $errors->first() }}
        </div>
        @endif

        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:30px;box-shadow:0 1px 3px rgba(0,0,0,0.05);display:flex;flex-direction:column;gap:24px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;border-bottom:1px solid #f3f4f6;padding-bottom:20px;">
                <div>
                    <span class="dashboard-kicker">MANAJEMEN PESANAN ADMIN</span>
                    <h1 style="font-size:24px;color:#111827;margin-top:4px;">Pesanan #{{ $order->order_number }}</h1>
                    <p style="font-size:14px;color:#6b7280;margin-top:4px;">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <span style="padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;text-transform:uppercase;
                        @if($order->status === 'completed') background:#d1fae5;color:#065f46;
                        @elseif($order->status === 'processing') background:#dbeafe;color:#1e40af;
                        @elseif($order->status === 'shipped') background:#e0e7ff;color:#3730a3;
                        @elseif($order->status === 'cancelled') background:#fee2e2;color:#991b1b;
                        @else background:#fef3c7;color:#92400e; @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span style="padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;
                        @if($order->payment_status === 'paid') background:#d1fae5;color:#065f46;
                        @else background:#f3f4f6;color:#4b5563; @endif">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:24px;">
                <div style="background:#f9fafb;padding:20px;border-radius:10px;border:1px solid #f3f4f6;">
                    <h2 style="font-size:16px;font-weight:600;color:#111827;margin-bottom:12px;">Informasi Pelanggan</h2>
                    <p style="font-weight:600;color:#111827;margin-bottom:4px;">{{ $order->customer_name ?: ($order->user?->name ?? 'Pelanggan') }}</p>
                    <p style="font-size:14px;color:#4b5563;margin-bottom:4px;">{{ $order->customer_phone ?: '—' }}</p>
                    <p style="font-size:14px;color:#4b5563;margin-bottom:8px;">{{ $order->customer_email ?: ($order->user?->email ?? '—') }}</p>
                    <p style="font-size:14px;color:#4b5563;line-height:1.5;">{{ $order->shipping_address }}</p>
                    @if ($order->notes)
                        <p style="font-size:13px;color:#6b7280;margin-top:8px;font-style:italic;">Catatan: {{ $order->notes }}</p>
                    @endif
                </div>

                <div style="background:#f9fafb;padding:20px;border-radius:10px;border:1px solid #f3f4f6;">
                    <h2 style="font-size:16px;font-weight:600;color:#111827;margin-bottom:12px;">Perbarui Status Pesanan</h2>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px;">Status Pesanan</label>
                            <select name="status" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;background:#fff;">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Baru)</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (Dikirim)</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:4px;">Status Pembayaran</label>
                            <select name="payment_status" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;background:#fff;">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid (Belum Lunas)</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded (Dikembalikan)</option>
                            </select>
                        </div>
                        <button type="submit" style="background:#111827;color:#fff;border:none;padding:10px 16px;border-radius:6px;font-weight:600;cursor:pointer;font-size:14px;margin-top:4px;">Simpan Perubahan Status</button>
                    </form>
                </div>
            </div>

            <div>
                <h2 style="font-size:18px;font-weight:600;color:#111827;margin-bottom:16px;">Daftar Produk Dipesan</h2>
                <div style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                    <table style="width:100%;border-collapse:collapse;text-align:left;font-size:14px;">
                        <thead>
                            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;color:#374151;">
                                <th style="padding:12px 16px;">Produk</th>
                                <th style="padding:12px 16px;">Harga Satuan</th>
                                <th style="padding:12px 16px;">Kuantitas</th>
                                <th style="padding:12px 16px;text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->order_items as $item)
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td style="padding:14px 16px;">
                                        <div style="display:flex;align-items:center;gap:12px;">
                                            @if ($item->product?->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                            @else
                                                <div style="width:40px;height:40px;background:#e5e7eb;border-radius:6px;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#4b5563;">{{ strtoupper(substr($item->product_name, 0, 1)) }}</div>
                                            @endif
                                            <div>
                                                <strong style="color:#111827;">{{ $item->product_name }}</strong>
                                                <div style="font-size:12px;color:#6b7280;">ID: #{{ $item->product_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:14px 16px;color:#4b5563;">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td style="padding:14px 16px;color:#4b5563;">{{ $item->quantity }}</td>
                                    <td style="padding:14px 16px;text-align:right;font-weight:600;color:#111827;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <div style="width:300px;display:flex;flex-direction:column;gap:8px;font-size:14px;">
                    <div style="display:flex;justify-content:space-between;color:#4b5563;">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;color:#4b5563;">
                        <span>Ongkos Kirim</span>
                        <span>{{ $order->shipping_fee == 0 ? 'Gratis' : 'Rp' . number_format($order->shipping_fee, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;border-top:1px solid #e5e7eb;padding-top:10px;font-size:16px;font-weight:bold;color:#111827;">
                        <span>Total Pembayaran</span>
                        <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
