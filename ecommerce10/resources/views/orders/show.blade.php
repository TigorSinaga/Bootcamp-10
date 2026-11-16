@extends('template.layout')

@section('title', 'Detail Pesanan #' . $order->order_number . ' — RuangBelanja')

@section('content')
@include('template.navbar')

<main class="page-content cart-page" style="max-width:900px;margin:40px auto;padding:0 20px;">
    <nav class="cart-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('orders.index') }}">Pesanan Saya</a>
        <span aria-hidden="true">/</span>
        <span>#{{ $order->order_number }}</span>
    </nav>

    @if (session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:14px 18px;border-radius:10px;margin-bottom:24px;font-weight:500;border-left: 4px solid #10B981;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:17px;padding:clamp(20px, 5vw, 40px);box-shadow:0 10px 30px #28204708;display:flex;flex-direction:column;gap:32px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:20px;border-bottom:1px solid #f3f4f6;padding-bottom:24px;">
            <div>
                <span class="cart-eyebrow">Detail Transaksi ✨</span>
                <h1 style="font-size: clamp(1.8rem, 4vw, 2.35rem); font-weight: 850; color:#111827; margin-top:6px; letter-spacing: -1px;">Pesanan #{{ $order->order_number }}</h1>
                <p style="font-size:14px;color:#6b7280;margin-top:6px;">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;">
                <span style="padding:7px 16px;border-radius:99px;font-size:12px;font-weight:750;text-transform:uppercase; letter-spacing: 0.05em;
                    @if($order->status === 'completed') background:#d1fae5;color:#065f46;
                    @elseif($order->status === 'processing') background:#dbeafe;color:#1e40af;
                    @elseif($order->status === 'shipped') background:#e0e7ff;color:#3730a3;
                    @elseif($order->status === 'cancelled') background:#fee2e2;color:#991b1b;
                    @else background:#fef3c7;color:#92400e; @endif">
                    Status: {{ $order->status }}
                </span>
                <span style="padding:7px 16px;border-radius:99px;font-size:12px;font-weight:750; letter-spacing: 0.05em; text-transform: uppercase;
                    @if($order->payment_status === 'paid') background:#d1fae5;color:#065f46;
                    @else background:#f3f4f6;color:#4b5563; @endif">
                    {{ $order->payment_status }}
                </span>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:24px;">
            <div style="background:#f9fafb;padding:24px;border-radius:14px;border:1px solid #f3f4f6;">
                <h2 style="font-size:15px;font-weight:800;color:#111827;margin-bottom:14px; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #6B7280;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Penerima
                </h2>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <p style="font-weight:750;color:#111827;font-size: 16px;">{{ $order->customer_name }}</p>
                    <p style="font-size:14px;color:#4b5563;">{{ $order->customer_phone }}</p>
                    <p style="font-size:14px;color:#4b5563;">{{ $order->customer_email }}</p>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                        <p style="font-size:14px;color:#4b5563;line-height:1.6;">{{ $order->shipping_address }}</p>
                    </div>
                    @if ($order->notes)
                        <div style="margin-top: 12px; padding: 10px 14px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <p style="font-size:13px;color:#6b7280;font-style:italic;line-height: 1.5;"><strong>Catatan:</strong> {{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div style="background:#f9fafb;padding:24px;border-radius:14px;border:1px solid #f3f4f6;">
                <h2 style="font-size:15px;font-weight:800;color:#111827;margin-bottom:14px; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #6B7280;"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    Pembayaran
                </h2>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <p style="font-weight:750;color:#111827;text-transform:uppercase; font-size: 16px; letter-spacing: 0.02em;">{{ str_replace('_', ' ', $order->payment_method ?? 'Bank Transfer') }}</p>
                    <p style="font-size:14px;color:#4b5563;line-height: 1.5;">Silakan lakukan pembayaran sesuai instruksi atau konfirmasi pesanan melalui WhatsApp kami.</p>
                    <div style="margin-top:16px;">
                        <a href="https://wa.me/62895380631090?text={{ urlencode('Halo, saya ingin mengonfirmasi pembayaran untuk pesanan #' . $order->order_number) }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items: center; gap: 8px; background:#059669; color:#fff; padding:12px 20px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:750; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            Konfirmasi WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 style="font-size:18px;font-weight:850;color:#111827;margin-bottom:16px; letter-spacing: -0.5px;">Daftar Produk</h2>
            <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden; background: #fff;">
                <table style="width:100%;border-collapse:collapse;text-align:left;font-size:14px;">
                    <thead>
                        <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;color:#374151;">
                            <th style="padding:16px 20px; font-weight: 750;">Produk</th>
                            <th style="padding:16px 20px; font-weight: 750;">Harga</th>
                            <th style="padding:16px 20px; font-weight: 750;">Jumlah</th>
                            <th style="padding:16px 20px; text-align:right; font-weight: 750;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->order_items as $item)
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td style="padding:16px 20px;">
                                    <div style="display:flex;align-items:center;gap:16px;">
                                        @if ($item->product?->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:54px;height:54px;object-fit:cover;border-radius:10px; background: #f3f4f6;">
                                        @else
                                            <div style="width:54px;height:54px;background:#f3f4f6;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#9CA3AF;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <strong style="color:#111827; font-weight: 750; font-size: 15px;">{{ $item->product_name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:16px 20px;color:#4b5563;">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td style="padding:16px 20px;color:#4b5563;">{{ $item->quantity }}</td>
                                <td style="padding:16px 20px;text-align:right;font-weight:800;color:#111827;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <div style="width:340px;display:flex;flex-direction:column;gap:12px;font-size:14px; background: #f9fafb; padding: 24px; border-radius: 14px; border: 1px solid #f3f4f6;">
                <div style="display:flex;justify-content:space-between;color:#6b7280;">
                    <span>Subtotal</span>
                    <span style="font-weight: 700; color: #111827;">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;color:#6b7280;">
                    <span>Ongkos Kirim</span>
                    <span style="font-weight: 700; color: #059669;">{{ $order->shipping_fee == 0 ? 'Gratis' : 'Rp' . number_format($order->shipping_fee, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;border-top:1px solid #e5e7eb;padding-top:12px;font-size:18px;font-weight:850;color:#111827; margin-top: 4px;">
                    <span>Total</span>
                    <span style="color: var(--purple);">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f3f4f6;padding-top:32px;">
            <a href="{{ route('orders.index') }}" style="color:#6b7280;text-decoration:none;font-weight:750; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar Pesanan
            </a>
            <a href="{{ route('home') }}" style="background:#111827;color:#fff;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:750; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#1f2937'" onmouseout="this.style.background='#111827'">Lanjut Belanja</a>
        </div>
    </div>
</main>

@include('template.footer')
@endsection
