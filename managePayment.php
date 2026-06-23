<?php
require_once 'function.php';

// Pastikan pengguna yang login adalah admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['flash_messages']['danger'] = "Akses ditolak! Halaman ini hanya bisa diakses oleh admin. Silahkan Login.";
    header("Location: login.php");
    exit;
}

// Proses konfirmasi pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $orderId = $_POST['order_id'];
    confirmOrder($orderId); // Fungsi untuk mengubah status pesanan menjadi 'Confirmed'
    $_SESSION['flash_messages']['success'] = "Pembayaran untuk Pesanan ID #$orderId berhasil dikonfirmasi.";
    header('Location: managePayment.php');
    exit;
}

// Cek jika permintaan POST untuk logout dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}

// Ambil daftar pesanan dengan status 'Pending'
$pendingOrders = getPendingOrders(); // Fungsi untuk mendapatkan pesanan dengan status 'Pending'
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payment</title>
    <link rel="stylesheet" href="css/managePayment.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2><i>LiLBRO</i> Admin</h2>
            <ul class="menu">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="addProduct.php">Manage Products</a></li>
                <li><a href="managePayment.php" class="active">Manage Payment</a></li>
                <li><a href="history.php">History Payment</a></li>
            </ul>
            <div class="logout">
                <form method="POST" action="">
                    <button type="submit" name="logout" class="button">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Daftar Pembayaran -->
            <section class="dashboard-overview">
                <h2>Daftar Checkout Products</h2>
                <?php if (!empty($pendingOrders)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Username</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingOrders as $order): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($order['order_id']); ?></td>
                                    <td><?= htmlspecialchars($order['username']); ?></td>
                                    <td>Rp <?= number_format($order['total_price'], 2, ',', '.'); ?></td>
                                    <td><?= htmlspecialchars($order['created_at']); ?></td>
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                            <button type="submit" name="confirm_payment" class="button button-confirm">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Tidak ada pembayaran tertunda saat ini.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>

</html>