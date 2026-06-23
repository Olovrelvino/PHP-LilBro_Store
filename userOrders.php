<?php
require_once 'function.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user']['login_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil daftar pesanan
$loginId = $_SESSION['user']['login_id'];
$orders = getUserOrders($loginId);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan Saya</title>
    <link rel="stylesheet" href="css/userOrders.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <h1>Pesanan Saya</h1>

    <!-- Tombol kembali ke Home -->
    <div class="button-container">
        <a href="home.php" class="home-icon">
            <i class="ri-home-4-line"></i>
        </a>
    </div>

    <!-- Tampilkan tabel daftar pesanan -->
    <?php if (!empty($orders)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td>Rp <?= number_format($order['total_price'], 2, ',', '.') ?></td>
                        <td>
                            <?= $order['status'] === 'Confirmed' 
                                ? '<span class="status-confirmed">Sudah Dikonfirmasi</span>' 
                                : '<span class="status-pending">Belum Dikonfirmasi</span>' ?>
                        </td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td>
                            <a href="orderDetails.php?order_id=<?= $order['order_id'] ?>">Lihat Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-orders">Tidak ada pesanan.</p>
    <?php endif; ?>
</body>
</html>
