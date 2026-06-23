<?php
require_once 'function.php';

// Pastikan pengguna yang login adalah admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['flash_messages']['danger'] = "Akses ditolak! Halaman ini hanya bisa diakses oleh admin. Silahkan Login.";
    header("Location: login.php");
    exit;
}

// Cek jika permintaan POST untuk logout dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}

$totalProducts = getTotalProducts(); // Inisialisasi fungsi total produk
$pendingPayments = getPendingPayments(); // Inisialisasi fungsi pembayaran tertunda
$confirmedPayments = getConfirmedPayments(); // Inisialisasi fungsi pembayaran dikonfirmasi

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2><i>LiLBRO</i> Admin</h2>
            <ul class="menu">
                <li><a href="admin.php" class="active">Dashboard</a></li>
                <li><a href="addProduct.php">Manage Products</a></li>
                <li><a href="managePayment.php">Manage Payment</a></li>
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

            <!-- Tampilkan Pesan Flash -->
            <?php if (isset($_SESSION['flash_messages'])): ?>
                <?php foreach ($_SESSION['flash_messages'] as $type => $message): ?>
                    <div class="alert alert-<?= $type; ?>">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash_messages']); ?>
            <?php endif; ?>

            <h1>Dashboard Admin</h1>
            <p>Selamat datang di halaman Dashboard Admin. Anda dapat melihat total produk dan total pembayaran tertunda</p>
            <!-- Tambahkan konten tambahan di sini -->
            <section class="dashboard-overview">
                <h2>Ringkasan</h2>
                <div class="overview-cards">
                    <!-- Total Produk -->
                    <a href="addProduct.php" class="card-link">
                        <div class="card">
                            <h3>Total Produk</h3>
                            <p><?= $totalProducts; ?></p>
                        </div>
                    </a>
                    <!-- Pembayaran Tertunda -->
                    <a href="managePayment.php" class="card-link">
                        <div class="card">
                            <h3>Pembayaran Tertunda</h3>
                            <p><?= $pendingPayments; ?></p>
                        </div>
                    </a>
                    <!-- Pembayaran Dikonfirmasi -->
                    <a href="history.php" class="card-link">
                        <div class="card">
                            <h3>Pembayaran Dikonfirmasi</h3>
                            <p><?= $confirmedPayments; ?></p>
                        </div>
                    </a>
                </div>
            </section>
        </main>
    </div>
</body>

</html>