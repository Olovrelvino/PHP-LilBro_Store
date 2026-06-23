<?php
require_once 'function.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['flash_messages']['danger'] = "Akses ditolak! Halaman ini hanya untuk admin.";
    header("Location: login.php");
    exit;
}

// Cek jika tombol logout ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}

// Ambil data riwayat pembelian
$orderHistory = getAllOrderHistory();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Riwayat Pembelian</title>
    <link rel="stylesheet" href="css/history.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2><i>LiLBRO</i> Admin</h2>
            <ul class="menu">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="addProduct.php">Manage Products</a></li>
                <li><a href="managePayment.php">Manage Payment</a></li>
                <li><a href="history.php" class="active">History Payment</a></li>
            </ul>
            <div class="logout">
                <form method="POST" action="">
                    <button type="submit" name="logout" class="button">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1>History Pembelian User</h1>
            <p>Tabel history pembelian oleh user.</p>

            <!-- Ringkasan Riwayat Pembelian -->
            <section class="dashboard-overview">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Username</th>
                            <th>Items</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orderHistory)): ?>
                            <?php foreach ($orderHistory as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                                    <td><?= htmlspecialchars($order['username']) ?></td>
                                    <td><?= htmlspecialchars($order['items']) ?></td>
                                    <td>Rp <?= number_format($order['total_price'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($order['status']) ?></td>
                                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Belum ada riwayat pembelian.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>
