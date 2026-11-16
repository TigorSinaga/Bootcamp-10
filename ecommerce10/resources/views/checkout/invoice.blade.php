@extends('template.layout')

@section('title', 'Invoice #' . $invoice['id'] . ' — RuangBelanja')

@section('content')
@include('template.navbar')

<main class="page-content cart-page invoice-page">
    <nav class="cart-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span aria-hidden="true">/</span>
        <span>Invoice</span>
    </nav>

    <section class="invoice-card">
        <div class="invoice-success">
            <div class="invoice-success-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <span class="cart-eyebrow">Checkout berhasil ✨</span>
            <h1>Pesananmu tercatat!</h1>
            <p>Gunakan invoice ini untuk mengonfirmasi pesanan melalui WhatsApp kami.</p>
        </div>

        <div class="invoice-meta">
            <div>
                <span>Nomor invoice</span>
                <strong>#{{ $invoice['id'] }}</strong>
            </div>
            <div>
                <span>Tanggal</span>
                <strong>{{ $invoice['created_at'] }}</strong>
            </div>
            <div>
                <span>Status</span>
                <strong class="invoice-pending" style="display: flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Menunggu konfirmasi
                </strong>
            </div>
        </div>

        <div class="invoice-details-grid">
            <section class="invoice-detail-block">
                <h2>Informasi penerima</h2>
                <strong>{{ $invoice['name'] }}</strong>
                <span>{{ $invoice['phone'] }}</span>
                <span>{{ $invoice['email'] }}</span>
                <p>{{ $invoice['address'] }}</p>
                @if ($invoice['delivery_note'] !== '')
                    <div style="margin-top: 10px; padding: 8px 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #f0f0f5;">
                        <small style="font-style: italic; color: #66677c;">Catatan: {{ $invoice['delivery_note'] }}</small>
                    </div>
                @endif
            </section>
            <section class="invoice-detail-block">
                <h2>Metode pembayaran</h2>
                <strong style="text-transform: uppercase;">{{ $invoice['payment_method'] }}</strong>
                <span>Silakan lakukan konfirmasi agar pesanan segera kami proses.</span>
            </section>
        </div>

        <section class="invoice-items">
            <h2>Rincian pesanan</h2>
            <div class="invoice-items-header" aria-hidden="true">
                <span>Produk</span>
                <span>Jumlah</span>
                <span>Total</span>
            </div>
            @foreach ($invoice['items'] as $item)
                <div class="invoice-item">
                    <strong style="color: var(--ink);">{{ $item['name'] }}</strong>
                    <span>{{ $item['quantity'] }} × Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                    <strong style="color: var(--ink);">Rp{{ number_format($item['total'], 0, ',', '.') }}</strong>
                </div>
            @endforeach
        </section>

        <div class="invoice-totals">
            <div><span>Subtotal ({{ $invoice['cart_count'] }} item)</span><strong>Rp{{ number_format($invoice['subtotal'], 0, ',', '.') }}</strong></div>
            <div><span>Ongkos kirim</span><strong style="color: #059669;">{{ $invoice['shipping_fee'] === 0 ? 'Gratis' : 'Rp'.number_format($invoice['shipping_fee'], 0, ',', '.') }}</strong></div>
            <div class="invoice-grand-total"><span>Total pembayaran</span><strong>Rp{{ number_format($invoice['total'], 0, ',', '.') }}</strong></div>
        </div>

        <div class="invoice-actions">
            <a class="btn btn-success" href="https://wa.me/62895380631090?text={{ urlencode('Halo, saya ingin mengonfirmasi pesanan dengan nomor pesanan #' . (int) $invoice['id']) }}" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #059669; border: none; border-radius: 12px; height: 52px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                Konfirmasi via WhatsApp
            </a>
            <a class="invoice-home-link" href="{{ route('home') }}" style="margin-top: 12px; font-weight: 750;">Kembali ke beranda</a>
        </div>
        <p class="invoice-temporary-note" style="margin-top: 24px; font-size: 0.7rem; background: #fff8f1; padding: 10px; border-radius: 8px; color: #9a6a00; border: 1px solid #ffe8cc;">
            <strong>Catatan:</strong> Invoice ini bersifat sementara dan tersimpan di sesi browser. Jangan tutup sesi sebelum mengirim konfirmasi WhatsApp.
        </p>
    </section>
</main>

@include('template.footer')
@endsection
