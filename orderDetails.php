<?php
require_once 'function.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user']['login_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID pesanan dari URL
$orderId = $_GET['order_id'] ?? null;

// Redirect jika ID pesanan tidak valid
if (!$orderId) {
    header("Location: userOrders.php");
    exit;
}

// Ambil detail pesanan
$orderDetails = getOrderDetails($orderId);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="css/orderDetails.css">
</head>
<body>
    <div class="receipt">
        <h1>Struk Belanja</h1>
        <p><strong>ID Pesanan:</strong> <?= htmlspecialchars($orderId) ?></p>
        <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i:s') ?></p>
        <hr>

        <?php if (!empty($orderDetails)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($orderDetails as $detail): ?>
                        <tr>
                            <td><?= htmlspecialchars($detail['product_name']) ?></td>
                            <td>Rp <?= number_format($detail['price'], 2, ',', '.') ?></td>
                            <td><?= $detail['quantity'] ?></td>
                            <td>Rp <?= number_format($detail['price'] * $detail['quantity'], 2, ',', '.') ?></td>
                        </tr>
                        <?php $total += $detail['price'] * $detail['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr>
            <p class="total"><strong>Total: Rp <?= number_format($total, 2, ',', '.') ?></strong></p>
        <?php else: ?>
            <p>Tidak ada detail untuk pesanan ini.</p>
        <?php endif; ?>
        
        <div class="button-container">
            <a href="userOrders.php" class="back-button">Kembali ke Pesanan Saya</a>
        </div>
    </div>
</body>
</html>
