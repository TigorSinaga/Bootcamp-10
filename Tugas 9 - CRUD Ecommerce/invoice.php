<?php
require_once __DIR__ . '/koneksi_db.php';

function escapeHtml(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$invoiceToken = $_GET['token'] ?? '';
if (!$orderId || !is_string($invoiceToken) || !preg_match('/^[a-f0-9]{64}$/', $invoiceToken)) {
    http_response_code(404);
    exit('Invoice tidak ditemukan.');
}

$orderStatement = $pdo->prepare(
    'SELECT o.id, o.user_id, o.customer_name, o.customer_phone, o.order_date, o.total_price, o.status, o.shipping_address,
            u.name AS account_name, u.phone AS account_phone
    FROM orders o
    INNER JOIN users u ON u.id = o.user_id
    WHERE o.id = :id AND o.invoice_token = :invoice_token'
);
$orderStatement->execute([
    ':id' => $orderId,
    ':invoice_token' => $invoiceToken,
]);
$order = $orderStatement->fetch();

if (!$order) {
    http_response_code(404);
    exit('Invoice tidak ditemukan.');
}

$itemStatement = $pdo->prepare(
    'SELECT oi.quantity, oi.price, oi.subtotal, p.name AS product_name
    FROM order_items oi
    INNER JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = :order_id
    ORDER BY oi.id'
);
$itemStatement->execute([':order_id' => $orderId]);
$items = $itemStatement->fetchAll();

$customerName = $order['customer_name'] ?: $order['account_name'];
$customerPhone = $order['customer_phone'] ?: $order['account_phone'];
$whatsappPhone = preg_replace('/\D+/', '', (string) $customerPhone);
if (str_starts_with($whatsappPhone, '0')) {
    $whatsappPhone = '62' . substr($whatsappPhone, 1);
}
$statusLabels = [
    'pending' => 'Menunggu pembayaran',
    'paid' => 'Dibayar',
    'processed' => 'Diproses',
    'shipped' => 'Dikirim',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
];
$statusLabel = $statusLabels[$order['status']] ?? $order['status'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #<?= (int) $order['id'] ?> | Etalase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            color: #202938;
        }

        .invoice-card {
            border: 0;
            border-radius: 1rem;
        }

        @media print {
            body {
                background: #fff;
            }

            .no-print {
                display: none !important;
            }

            .invoice-card {
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark no-print">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">Etalase</a>
            <a class="btn btn-outline-light" href="home.php">Kembali ke beranda</a>
        </div>
    </nav>

    <main class="container py-5">
        <article class="card invoice-card shadow-sm mx-auto" style="max-width: 900px;">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 border-bottom pb-4 mb-4">
                    <div>
                        <p class="text-uppercase text-secondary small fw-semibold mb-1">Etalase</p>
                        <h1 class="h2 fw-bold mb-1">Invoice</h1>
                        <p class="text-secondary mb-0">Nomor pesanan #<?= (int) $order['id'] ?></p>
                    </div>
                    <div class="text-sm-end">
                        <div class="text-secondary small">Tanggal pesanan</div>
                        <div class="fw-semibold"><?= escapeHtml(date('d-m-Y H:i', strtotime($order['order_date']))) ?></div>
                        <span class="badge text-bg-warning mt-2"><?= escapeHtml($statusLabel) ?></span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6">
                        <h2 class="h6 text-uppercase text-secondary">Customer</h2>
                        <div class="fw-semibold"><?= escapeHtml($customerName) ?></div>
                        <div><?= escapeHtml($customerPhone ?? '—') ?></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 class="h6 text-uppercase text-secondary">Alamat Pengiriman</h2>
                        <div><?= nl2br(escapeHtml($order['shipping_address'])) ?></div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= escapeHtml($item['product_name']) ?></td>
                                    <td class="text-end text-nowrap">Rp<?= number_format((float) $item['price'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= (int) $item['quantity'] ?></td>
                                    <td class="text-end text-nowrap">Rp<?= number_format((float) $item['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end text-primary text-nowrap">Rp<?= number_format((float) $order['total_price'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <p class="small text-secondary mt-4 mb-0">Simpan halaman ini sebagai bukti pesanan Anda.</p>
                <a class="btn btn-success mt-4" href="https://wa.me/62895380631090?text=<?= urlencode('Halo, saya ingin mengonfirmasi pesanan dengan nomor pesanan #' . (int) $order['id']) ?>" target="_blank" rel="noopener noreferrer">Konfirmasi melalui WhatsApp</a>
                <div class="d-flex flex-wrap gap-2 mt-4 no-print">
                    <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Invoice</button>
                    <a class="btn btn-outline-secondary" href="home.php">Lanjut Belanja</a>
                </div>
            </div>
        </article>
    </main>
</body>

</html>