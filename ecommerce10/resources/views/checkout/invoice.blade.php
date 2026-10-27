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
            <span class="invoice-success-icon" aria-hidden="true">✓</span>
            <span class="cart-eyebrow">Checkout berhasil</span>
            <h1>Pesananmu tercatat!</h1>
            <p>Gunakan invoice ini untuk mengonfirmasi pesanan melalui WhatsApp.</p>
        </div>

        <div class="invoice-meta">
            <div>
                <span>Nomor invoice sementara</span>
                <strong>#{{ $invoice['id'] }}</strong>
            </div>
            <div>
                <span>Tanggal</span>
                <strong>{{ $invoice['created_at'] }}</strong>
            </div>
            <div>
                <span>Status</span>
                <strong class="invoice-pending">Menunggu konfirmasi</strong>
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
                    <small>Catatan kurir: {{ $invoice['delivery_note'] }}</small>
                @endif
            </section>
            <section class="invoice-detail-block">
                <h2>Metode pembayaran</h2>
                <strong>{{ $invoice['payment_method'] }}</strong>
                <span>Pembayaran belum dikonfirmasi.</span>
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
                    <strong>{{ $item['name'] }}</strong>
                    <span>{{ $item['quantity'] }} × Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                    <strong>Rp{{ number_format($item['total'], 0, ',', '.') }}</strong>
                </div>
            @endforeach
        </section>

        <div class="invoice-totals">
            <div><span>Subtotal ({{ $invoice['cart_count'] }} item)</span><strong>Rp{{ number_format($invoice['subtotal'], 0, ',', '.') }}</strong></div>
            <div><span>Ongkos kirim</span><strong>{{ $invoice['shipping_fee'] === 0 ? 'Gratis' : 'Rp'.number_format($invoice['shipping_fee'], 0, ',', '.') }}</strong></div>
            <div class="invoice-grand-total"><span>Total pembayaran</span><strong>Rp{{ number_format($invoice['total'], 0, ',', '.') }}</strong></div>
        </div>

        <div class="invoice-actions">
            <a class="btn btn-success mt-4" href="https://wa.me/62895380631090?text={{ urlencode('Halo, saya ingin mengonfirmasi pesanan dengan nomor pesanan #' . (int) $invoice['id']) }}" target="_blank" rel="noopener noreferrer">Konfirmasi melalui WhatsApp</a>
            <a class="invoice-home-link" href="{{ route('home') }}">Kembali ke beranda</a>
        </div>
        <p class="invoice-temporary-note">Invoice ini bersifat sementara dan tersimpan di sesi browser. Jangan tutup sesi sebelum mengirim konfirmasi WhatsApp.</p>
    </section>
</main>

@include('template.footer')
@endsection
