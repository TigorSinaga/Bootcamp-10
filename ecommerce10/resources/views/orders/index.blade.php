@extends('template.layout')

@section('title', 'Riwayat Pesanan — RuangBelanja')

@section('content')
@include('template.navbar')

<main class="page-content cart-page" style="max-width:900px;margin:40px auto;padding:0 20px;">
    <nav class="cart-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span aria-hidden="true">/</span>
        <span>Riwayat Pesanan</span>
    </nav>

    <div class="cart-heading" style="margin-bottom:32px;">
        <div>
            <span class="cart-eyebrow">Aktivitas belanja ✨</span>
            <h1>Pesanan Saya</h1>
            <p>Daftar semua pesanan yang pernah kamu buat.</p>
        </div>
        <a class="cart-continue-link" href="{{ route('home') }}">← Lanjut belanja</a>
    </div>

    @if (session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:14px 18px;border-radius:10px;margin-bottom:24px;font-weight:500;border-left: 4px solid #10B981;">
            {{ session('success') }}
        </div>
    @endif

    @if ($orders->count() > 0)
        <div style="display:flex;flex-direction:column;gap:16px;">
            @foreach ($orders as $order)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:24px;display:flex;flex-direction:column;gap:16px;transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onclick="window.location='{{ route('orders.show', $order) }}'">
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;border-bottom:1px solid #f3f4f6;padding-bottom:16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: #f3f4f6; border-radius: 10px; display: grid; place-items: center; color: #4B5563;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </div>
                            <div>
                                <strong style="font-size:16px;color:#111827;letter-spacing: -0.3px;">#{{ $order->order_number }}</strong>
                                <div style="font-size:13px;color:#6b7280;margin-top: 1px;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <span style="padding:6px 12px;border-radius:99px;font-size:11px;font-weight:750;text-transform:uppercase;letter-spacing: 0.05em;
                                @if($order->status === 'completed') background:#d1fae5;color:#065f46;
                                @elseif($order->status === 'processing') background:#dbeafe;color:#1e40af;
                                @elseif($order->status === 'shipped') background:#e0e7ff;color:#3730a3;
                                @elseif($order->status === 'cancelled') background:#fee2e2;color:#991b1b;
                                @else background:#fef3c7;color:#92400e; @endif">
                                {{ $order->status }}
                            </span>
                            <span style="padding:6px 12px;border-radius:99px;font-size:11px;font-weight:750;letter-spacing: 0.05em; text-transform: uppercase;
                                @if($order->payment_status === 'paid') background:#d1fae5;color:#065f46;
                                @else background:#f3f4f6;color:#4b5563; @endif">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                        <div>
                            <span style="font-size:14px;color:#6b7280;">Total Pembayaran</span>
                            <div style="font-size:18px;font-weight:800;color:#111827;margin-top: 2px;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" style="background:#111827;color:#fff;padding:10px 20px;border-radius:10px;text-decoration:none;font-size:14px;font-weight:700; transition: background 0.2s;" onmouseover="this.style.background='#1f2937'" onmouseout="this.style.background='#111827'">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:32px; display: flex; justify-content: center;">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="cart-empty" style="text-align:center;padding:80px 20px; background: #fff; border-radius: 17px; border: 1px solid #e5e7eb;">
            <div class="empty-cart-icon" style="width: 80px; height: 80px; background: #f3f4f6; border-radius: 24px; display: grid; place-items: center; margin: 0 auto 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9CA3AF;"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h2 style="font-weight: 850; letter-spacing: -0.5px;">Belum ada pesanan</h2>
            <p style="color: #6B7280; margin-top: 8px;">Kamu belum pernah membuat pesanan. Yuk mulai belanja produk favoritmu!</p>
            <a class="checkout-btn empty-cart-btn" href="{{ route('home') }}#produk" style="display:inline-flex; margin-top:24px; min-width: 200px;">Mulai belanja <span aria-hidden="true" style="margin-left: 8px;">→</span></a>
        </div>
    @endif
</main>

@include('template.footer')
@endsection
