<?php
require_once 'function.php';

if (!isset($_SESSION['user']['login_id'])) {
    header("Location: login.php");
    exit;
}

$loginId = $_SESSION['user']['login_id']; // Ambil login_id dari session
$cartItems = getCartItems($loginId); // inisialisasi ambil item dari keranjang
$totalPrice = 0; // Inisialisasi total harga

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['checkout'])) {
        // Ambil data keranjang dan total harga
        $cartItems = getCartItems($loginId); // Ambil item dari keranjang
        $totalPrice = array_reduce($cartItems, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        // Lakukan checkout
        $orderId = checkout($loginId, $totalPrice); // Proses checkout

        // Arahkan ke halaman userOrders.php dengan ID pesanan
        header("Location: userOrders.php?order_id=$orderId");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_item'])) {
        $cartId = $_POST['cart_id']; // Ambil ID item dari form
        removeCartItem($cartId); // Panggil fungsi untuk menghapus item
        header("Location: cart.php"); // Redirect untuk memperbarui tampilan keranjang
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>
    <h1>Keranjang Belanja</h1>
    <div class="cart">
        <!-- Tombol Kembali ke Shop -->
        <div class="back-to-shop">
            <a href="shop.php" class="back-button">Kembali ke Shop</a>
        </div>

        <?php if (!empty($cartItems)): ?>
            <?php foreach ($cartItems as $item): ?>
                <?php $subtotal = $item['price'] * $item['quantity']; ?>
                <?php $totalPrice += $subtotal; ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                    <p><?= htmlspecialchars($item['product_name']) ?></p>
                    <p>Harga: Rp <?= number_format($item['price'], 2, ',', '.') ?></p>
                    <p>Jumlah: <?= $item['quantity'] ?></p>
                    <p>Subtotal: Rp <?= number_format($subtotal, 2, ',', '.') ?></p>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                        <button type="submit" name="remove_item">Hapus</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <p><strong>Total Harga: Rp <?= number_format($totalPrice, 2, ',', '.') ?></strong></p>
            <form method="POST" action="cart.php">
                <button type="submit" name="checkout">Checkout</button>
            </form>
        <?php else: ?>
            <p>Keranjang kosong.</p>
        <?php endif; ?>
    </div>
</body>

</html>