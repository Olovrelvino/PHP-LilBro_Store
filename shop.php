<?php
require_once 'function.php';

// Cek jika tombol logout ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}

// Ambil data produk dari database, dikelompokkan berdasarkan kategori
$groupedProducts = getProductsGroupedByCategory();

if (!isset($_SESSION['user']['login_id'])) {
    header("Location: login.php");
    exit;
}

//AMBIL LOGIN_ID DARI SESSION
$loginId = $_SESSION['user']['login_id'];

//PANGGIL FUNGSI TAMBAH PRODUCT KE KERANJANG
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        addToCart($loginId, $productId, 1);
        $_SESSION['flash_messages']['success'] = "Produk berhasil ditambahkan ke keranjang!";
        header('Location: shop.php');
        exit;
    }

    //MEMANGGIL FUNGSI BUY NOW LANGUSNG MASUK KE KERANJANG
    if (isset($_POST['buy_now'])) {
        $productId = $_POST['product_id'];
        addToCart($loginId, $productId, 1);
        header('Location: cart.php');
        exit;
    }
}

//AMBIL NAMA USER DARI SESSION LOGIN USER
$username = $_SESSION['user']['username'];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Minuman Alkohol</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/shop.css">
</head>

<body>

    <!-- Navbar -->
    <div class="navbar" id="navbar">
        <div class="brand">LiLBRO</div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="userOrders.php">Orders</a>
            <div class="icons">
                <a href="cart.php"><i class="ri-shopping-cart-line"></i></a>
                <a href="#"><?php echo htmlspecialchars($username); ?><i class="ri-user-line"></i></a>
                <form action="shop.php" method="POST" style="display: inline;">
                    <button class="logout-button" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
        <div class="hamburger" onclick="toggleMenu()">
            <i class="ri-menu-line"></i>
        </div>
    </div>

    <main>
        <div class="categories">
            <h2>Kategori</h2>

            <!-- Tampilkan Kategori -->
            <?php foreach ($groupedProducts as $category => $products): ?>
                <div class="category">
                    <h3><?= ucfirst(str_replace('_', ' ', $category)) ?></h3>
                    <div class="products">
                        <?php foreach ($products as $product): ?>
                            <div class="product <?= $product['status'] === 'out_of_stock' ? 'out-of-stock' : '' ?>">
                                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <p><?= htmlspecialchars($product['name']) ?></p>
                                <p>Rp <?= number_format($product['price'], 2, ',', '.') ?></p>
                                <?php if ($product['status'] === 'out_of_stock'): ?>
                                    <span class="status-label">Out of Stock</span>
                                <?php else: ?>
                                    <div class="product-buttons">
                                        <form method="POST" action="shop.php">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <button type="submit" name="buy_now" class="buy-now">Beli Langsung</button>
                                            <button type="submit" name="add_to_cart" class="add-to-cart">+ Keranjang</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer id="footer">
        <div class="footer-content">
            <p>&copy; 2024 LiLBRO - All Rights Reserved.</p>
            <div class="footer-links">
                <a href="#welcome">Home</a> |
                <a href="#about-contact">About Us</a> |
                <a href="#shop">Shop</a>
            </div>
            <p>Follow us on:</p>
            <div class="footer-icons">
                <a href="#"><i class="ri-facebook-circle-line"></i></a>
                <a href="#"><i class="ri-instagram-line"></i></a>
                <a href="#"><i class="ri-twitter-line"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Toggle menu untuk layar kecil
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            const hamburger = document.querySelector('.hamburger i');

            if (navLinks.classList.contains('open')) {
                navLinks.classList.remove('open');
                hamburger.classList.remove('ri-close-line');
                hamburger.classList.add('ri-menu-line');
            } else {
                navLinks.classList.add('open');
                hamburger.classList.remove('ri-menu-line');
                hamburger.classList.add('ri-close-line');
            }
        }

        // Tambahkan event listener untuk menutup menu saat mengklik link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                const navLinks = document.getElementById('navLinks');
                const hamburger = document.querySelector('.hamburger i');
                navLinks.classList.remove('open');
                hamburger.classList.remove('ri-close-line');
                hamburger.classList.add('ri-menu-line');
            });
        });
    </script>

</body>

</html>