<?php
require_once 'function.php';

// Cek apakah pengguna sudah login dan memiliki peran "user"
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    $_SESSION['flash_messages']['danger'] = "Anda belum login! Silahkan login terlebih dahulu.";
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user']['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiLBRO - E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 40px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            z-index: 1000;
            box-sizing: border-box;
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.5);
        }

        .navbar .brand {
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 90px;
        }

        .navbar .nav-links {
            display: flex;
            gap: 30px;
            margin-right: 150px;
            align-items: center;
        }

        .navbar .nav-links a {
            color: white;
            font-size: 15px;
            text-decoration: none;
        }

        .navbar .nav-links a:hover {
            color: #00ffff;
        }

        .navbar .icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar .icons img {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }

        .hamburger {
            display: none;
            cursor: pointer;
        }

        .hamburger i {
            font-size: 24px;
            color: white;
        }

        .logout-button {
            color: white;
            font-size: 16px;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .logout-button:hover {
            color: #ff4d4d;
        }

        /* Perbaikan untuk navbar mobile */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }

            .navbar .brand {
                margin-left: 0;
                z-index: 1001;
            }

            /* Tampilkan hamburger */
            .hamburger {
                display: block;
                z-index: 1001;
                cursor: pointer;
            }

            /* Sembunyikan nav-links secara default */
            .nav-links {
                display: none !important;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(0, 0, 0, 0.95);
                flex-direction: column;
                padding: 10px 0;
                margin: 0;
                z-index: 1000;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            /* Tampilkan nav-links saat class 'open' aktif */
            .nav-links.open {
                display: flex !important;
            }

            /* Atur ulang margin untuk mobile */
            .navbar .nav-links {
                margin-right: 0;
            }

            /* Atur tampilan menu items */
            .nav-links a {
                padding: 8px 20px;
                font-size: 16px !important;
            }

            /* Atur icons untuk mobile */
            .navbar .icons {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                padding: 8px 20px;
            }

            /* Tambahan styling untuk logout button */
            .logout-button {
                width: 100%;
                text-align: left;
                padding: 8px 20px;
                color: white;
                font-size: 16px;
                cursor: pointer;
            }
        }

        /* Bagian Selamat Datang */
        .welcome {
            position: relative;
            background-image: url('images/home-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 250px 20px;
        }

        /* Overlay gelap untuk latar belakang */
        .welcome::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .welcome h1,
        .welcome p {
            position: relative;
            z-index: 2;

        }

        .welcome h1 {
            font-size: 50px;
            margin: 0;
            color: #f5f5f5;
        }

        .welcome p {
            font-size: 20px;
            margin: 10px 0 0;
            color: #f5f5f5;
        }

        /* Carousel */
        .carousel-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }

        .carousel {
            position: relative;
            width: 200px;
            height: 200px;
            overflow: hidden;
        }

        .carousel img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: absolute;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .carousel img.active {
            opacity: 1;
        }

        .carousel .prev,
        .carousel .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            z-index: 10;
            border-radius: 50%;
        }

        .carousel .prev {
            left: -30px;
        }

        .carousel .next {
            right: -30px;
        }

        /* Produk */
        .products {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin: 30px auto;
            max-width: 900px;
        }

        .product {
            width: 150px;
            text-align: center;
        }

        .product img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .product p {
            font-size: 16px;
            margin: 0;
        }

        #about-contact {
            padding: 40px 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background-color: #f9f9f9;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
            gap: 20px;
        }

        #about-us {
            width: 58%;
            padding-right: 20px;
            box-sizing: border-box;
            font-size: 16px;
            line-height: 1.8;
        }

        #contact-us {
            width: 35%;
            padding-left: 20px;
            border-left: 2px solid #ddd;
            box-sizing: border-box;
        }

        #about-us h2,
        #contact-us h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #00ffff;
            display: inline-block;
            padding-bottom: 5px;
        }

        #about-us p,
        #contact-us p {
            color: #555;
            margin-bottom: 10px;
        }

        #contact-us i {
            margin-right: 10px;
            font-size: 20px;
            color: #000;
        }

        @media (max-width: 768px) {
            #about-contact {
                flex-direction: column;
                align-items: center;
            }

            #about-us,
            #contact-us {
                width: 100%;
                padding: 10px;
                border-left: none;
                border-top: 2px solid #ddd;
            }
        }

        #footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 10px;
            margin-top: 20px;
        }

        #footer .footer-content p {
            margin: 5px 0;
            font-size: 14px;
            line-height: 1.5;
        }

        #footer .footer-links {
            margin: 10px 0;
            font-size: 14px;
        }

        #footer .footer-links a {
            color: #00ffff;
            text-decoration: none;
            margin: 0 5px;
        }

        #footer .footer-links a:hover {
            text-decoration: underline;
        }

        #footer .footer-icons {
            margin: 10px 0;
        }

        #footer .footer-icons a {
            color: #fff;
            font-size: 24px;
            margin: 0 10px;
            transition: color 0.3s;
        }

        #footer .footer-icons a:hover {
            color: #00ffff;
        }
    </style>
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
                <form action="" method="POST" style="display: inline;">
                    <button class="logout-button" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
        <div class="hamburger" onclick="toggleMenu()">
            <i class="ri-menu-line"></i>
        </div>
    </div>

    <!-- Bagian Selamat Datang -->
    <section id="welcome" class="welcome">
        <h1>Selamat Datang di LiLBRO</h1>
        <p>Tempat terbaik untuk mendapatkan produk favorit Anda</p>
    </section>

    <h2>Our Product</h2>

    <!-- Carousel -->
    <section id="shop" class="carousel-container">
        <div class="carousel" id="carousel">
            <button class="prev" onclick="prevSlide()">&#10094;</button>
            <img src="images/beer.png" alt="Beer" class="active">
            <img src="images/import.png" alt="Macallan">
            <img src="images/local.png" alt="Anggur Merah">
            <img src="images/vodka.png" alt="Vodka">
            <img src="images/whiskey.png" alt="Whiskey">
            <img src="images/wine.png" alt="Wine">
            <button class="next" onclick="nextSlide()">&#10095;</button>
        </div>
    </section>

    <!-- Produk -->
    <section id="products" class="products">
        <div class="product">
            <img src="images/beer.png" alt="Beer">
            <p>Beer</p>
        </div>
        <div class="product">
            <img src="images/import.png" alt="Macallan">
            <p>Macallan</p>
        </div>
        <div class="product">
            <img src="images/local.png" alt="Anggur Merah">
            <p>Anggur Merah</p>
        </div>
        <div class="product">
            <img src="images/vodka.png" alt="Vodka">
            <p>Vodka</p>
        </div>
        <div class="product">
            <img src="images/whiskey.png" alt="Whiskey">
            <p>Whiskey</p>
        </div>
        <div class="product">
            <img src="images/wine.png" alt="Wine">
            <p>Wine</p>
        </div>
        <div class="product">
            <img src="images/vibe.png" alt="Vibe">
            <p>Vibe</p>
        </div>
        <div class="product">
            <img src="images/arbal.png" alt="Arak">
            <p>Arak Bali</p>
        </div>
        <div class="product">
            <img src="images/kawa.png" alt="Kawa">
            <p>Kawa - Kawa</p>
        </div>
        <div class="product">
            <img src="images/smirnoff.png" alt="Smirnoff">
            <p>Smirnoff</p>
        </div>
    </section>

    <section id="about-contact">
        <div id="about-us">
            <h2>About Us</h2>
            <p>
                Selamat datang di <strong>LiLBRO</strong>! Kami adalah toko online yang menawarkan berbagai pilihan minuman alkohol premium
                yang dirancang untuk memenuhi selera Anda. Dari anggur berkualitas, bir klasik, hingga spirit memikat, koleksi kami hadir
                untuk melengkapi setiap momen spesial Anda.
            </p>
            <p>
                Di LiLBRO, setiap minuman memiliki cerita. Kami hanya menyediakan produk dari merek-merek ternama yang sudah dipercaya
                secara global. Temukan rasa yang sesuai dengan suasana hati Anda – baik untuk perayaan, waktu santai di rumah, atau
                sekadar menikmati malam yang tenang.
            </p>
            <p>
                Mari bergabung dengan komunitas LiLBRO dan jadikan setiap momen lebih berkesan bersama kami.
            </p>
        </div>

        <div id="contact-us">
            <h2>Contact Us</h2>
            <p>
                <i class="ri-whatsapp-line"></i> +62 812-3456-7890
            </p>
            <p>
                <i class="ri-map-pin-line"></i> LiLBRO Store, Jl. Tentara Pelajar No. 17, Ambarawa
            </p>
            <p>
                <i class="ri-mail-line"></i> LiLBRO17@gmail.com
            </p>
            <p>
                <i class="ri-time-line"></i> Senin - Jumat, 09:00 - 18:00 WIB
            </p>
        </div>
    </section>

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
        // Navbar transparan saat scroll
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

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

        // Carousel
        let currentIndex = 0;
        const images = document.querySelectorAll('.carousel img');

        function updateCarousel() {
            images.forEach((img, index) => {
                img.classList.remove('active');
                if (index === currentIndex) {
                    img.classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % images.length;
            updateCarousel();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateCarousel();
        }

        // Auto-slide setiap 3 detik
        setInterval(nextSlide, 3000);
    </script>
</body>

</html>