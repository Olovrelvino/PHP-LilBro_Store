-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Sep 2026 pada 15.39
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trpw`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`cart_id`, `login_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(56, 1, 12, 1, '2026-05-22 12:26:26', '2026-05-22 12:26:26'),
(57, 1, 21, 1, '2026-05-22 12:26:36', '2026-05-22 12:26:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `role`) VALUES
(1, 'jackies', '$2y$10$YNZ4rqNgrAukpolysaKAr.Gst3cueIilY9r7CyRzQ4zZIGWI4U4Xm', 'user'),
(2, 'jon', '$2y$10$uzbAZ4xFn1823DeAtQystekhUTjvoF5zB5HxRM.vRk5jn.ALvghWa', 'admin'),
(4, 'aegon', '$2y$10$30qOsmBviqy39t8OtDnuwO31rbSFbsdDg00aVY05jLoFIdIFG7bsS', 'user'),
(6, 'suceng', '$2y$10$yx6KISYcr.OwtzWQcGrF0uc79hrnGaA.nSw.gLVmgUk8FoLmepUwK', 'user'),
(8, 'cessa', '$2y$10$reld3p49CbXkM9it7x4gPuOb7blC4KEfpd4IaXzMjKyhvLfIF546y', 'admin'),
(9, 'martin', '$2y$10$/vEc1jxEV3/7XmDmEIjoxeGvdWYC/RMybsC7uolhpTcson/O6snOe', 'user'),
(10, 'vino', '$2y$10$njOK03W2PVkXs4wPMihqAeB1FFaniI8TxVxEyirRnsWbiiAbx8hMm', 'user'),
(11, 'jack', '$2y$10$dCJU1jic.wiNkyVpP7hqH.2yYpbtu6k8SaMt9onLchh/hTjSyvlHG', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`order_id`, `login_id`, `total_price`, `status`, `created_at`) VALUES
(2, 1, '110000.00', 'Confirmed', '2024-11-23 19:07:43'),
(3, 1, '2559000.00', 'Confirmed', '2024-11-23 20:22:18'),
(4, 4, '2274000.00', 'Confirmed', '2024-11-24 07:38:55'),
(5, 4, '2159000.00', 'Confirmed', '2024-11-24 13:24:34'),
(6, 4, '4463000.00', 'Confirmed', '2024-11-26 17:06:25'),
(7, 6, '1099000.00', 'Confirmed', '2024-11-26 17:09:00'),
(8, 4, '2179000.00', 'Confirmed', '2024-11-27 10:17:58'),
(9, 4, '2179000.00', 'Confirmed', '2024-11-27 10:32:10'),
(10, 6, '220000.00', 'Confirmed', '2025-01-22 12:59:24'),
(11, 1, '2259000.00', 'Confirmed', '2025-06-17 15:41:30'),
(12, 9, '40000.00', 'Confirmed', '2025-06-17 15:46:42'),
(13, 10, '2259000.00', 'Confirmed', '2025-09-14 14:49:39'),
(14, 11, '2109000.00', 'Confirmed', '2026-05-04 06:37:31'),
(15, 11, '110000.00', 'Pending', '2026-05-04 06:46:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 2, 4, 1, '60000.00'),
(2, 2, 2, 1, '50000.00'),
(3, 3, 12, 1, '150000.00'),
(4, 3, 13, 1, '2109000.00'),
(5, 3, 3, 1, '300000.00'),
(7, 4, 13, 1, '2109000.00'),
(8, 4, 8, 1, '65000.00'),
(9, 5, 2, 1, '50000.00'),
(10, 5, 13, 1, '2109000.00'),
(11, 6, 13, 2, '2109000.00'),
(13, 6, 7, 1, '200000.00'),
(14, 7, 21, 1, '300000.00'),
(15, 7, 19, 1, '799000.00'),
(16, 8, 24, 1, '70000.00'),
(17, 8, 13, 1, '2109000.00'),
(18, 9, 24, 1, '70000.00'),
(19, 9, 13, 1, '2109000.00'),
(20, 10, 24, 1, '70000.00'),
(21, 10, 12, 1, '150000.00'),
(22, 11, 12, 1, '150000.00'),
(23, 11, 13, 1, '2109000.00'),
(24, 12, 15, 1, '40000.00'),
(25, 13, 12, 1, '150000.00'),
(26, 13, 13, 1, '2109000.00'),
(27, 14, 13, 1, '2109000.00'),
(28, 15, 4, 1, '60000.00'),
(29, 15, 2, 1, '50000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','out_of_stock') NOT NULL DEFAULT 'available',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(50) NOT NULL DEFAULT 'other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `status`, `image_path`, `created_at`, `updated_at`, `category`) VALUES
(1, 'Smirnoff', '230000.00', 'available', 'uploads/6740760c3a9e0-smirnoff.png', '2024-11-22 09:12:41', '2024-11-24 13:19:08', 'vodka'),
(2, 'Heinken Pilsener', '50000.00', 'available', 'uploads/67404b65ae1af-beer.png', '2024-11-22 09:14:13', '2024-11-22 09:41:41', 'beer'),
(3, 'Vibe Leci', '300000.00', 'available', 'uploads/67405bb419bb0-vibe.png', '2024-11-22 10:23:48', '2024-11-27 03:48:51', 'vodka'),
(4, 'Guinness', '60000.00', 'available', 'uploads/674076877a4eb-guinness.png', '2024-11-22 12:18:15', '2024-11-22 12:18:15', 'beer'),
(5, 'McDonal', '70000.00', 'available', 'uploads/67407718ab49a-McDonal.png', '2024-11-22 12:20:40', '2024-11-22 12:20:40', 'whiskey'),
(6, 'Wine Sababay', '1190000.00', 'available', 'uploads/6740784b2cf4b-wine.png', '2024-11-22 12:25:47', '2024-11-22 15:19:36', 'import_products'),
(7, 'Arak Bali', '200000.00', 'out_of_stock', 'uploads/67409f30c3d4f-arbal.png', '2024-11-22 15:11:44', '2025-06-17 15:38:11', 'local_products'),
(8, 'OT Anggur Merah', '65000.00', 'available', 'uploads/67409f627dd6c-local.png', '2024-11-22 15:12:34', '2024-11-22 15:12:34', 'local_products'),
(9, 'Kawa - Kawa', '75000.00', 'available', 'uploads/67409f8a49c73-kawa.png', '2024-11-22 15:13:14', '2024-11-22 15:13:14', 'local_products'),
(10, 'Jack Daniels', '470000.00', 'available', 'uploads/6740a0aac064e-jackDaniels.png', '2024-11-22 15:18:02', '2024-11-22 15:18:02', 'whiskey'),
(11, 'Wild Turkey', '1200000.00', 'available', 'uploads/6740a0f994092-wildTurkey.png', '2024-11-22 15:19:21', '2025-01-22 13:01:54', 'import_products'),
(12, 'Iceland Original', '150000.00', 'available', 'uploads/674238bd7cdd8-vodka.png', '2024-11-23 20:19:09', '2024-11-23 20:19:09', 'vodka'),
(13, 'Macallan 12', '2109000.00', 'available', 'uploads/6742394c0f357-import.png', '2024-11-23 20:21:32', '2024-11-23 20:21:32', 'import_products'),
(15, 'Bintang', '40000.00', 'available', 'uploads/67432210d9cc9-bintang.png', '2024-11-24 12:54:40', '2024-11-24 12:54:40', 'beer'),
(16, 'Prost', '55000.00', 'available', 'uploads/6743228acab3e-prost.png', '2024-11-24 12:56:42', '2024-11-24 12:56:42', 'beer'),
(17, 'Smirnoff Beer', '120000.00', 'available', 'uploads/67432308ca9f0-smirnoffBir.png', '2024-11-24 12:58:48', '2024-11-24 12:58:48', 'beer'),
(18, 'Bells', '429000.00', 'available', 'uploads/67432343118fd-bells.png', '2024-11-24 12:59:47', '2024-11-24 12:59:47', 'import_products'),
(19, 'Jack Daniels Honey', '799000.00', 'available', 'uploads/674323b821a87-jackDanielsHoney.png', '2024-11-24 13:01:44', '2025-09-14 14:52:42', 'whiskey'),
(20, 'Amer Gold', '80000.00', 'available', 'uploads/67432af40263f-amerGold.png', '2024-11-24 13:32:36', '2024-11-24 13:32:36', 'local_products'),
(21, 'Vibe Blacktea', '300000.00', 'available', 'uploads/67432d4c7d92f-vibeBlacktea.png', '2024-11-24 13:42:36', '2024-11-24 13:42:36', 'vodka'),
(22, 'Smirnoff Green', '289000.00', 'available', 'uploads/67432d7d18912-smirnoffGreen.png', '2024-11-24 13:43:25', '2024-11-24 13:43:25', 'vodka'),
(23, 'Vibe Peach', '300000.00', 'available', 'uploads/67432e15994be-vibePeach.png', '2024-11-24 13:45:57', '2024-11-24 13:45:57', 'vodka'),
(24, 'Anker', '70000.00', 'available', 'uploads/6746ecad3e7ab-anker.png', '2024-11-27 09:55:57', '2024-11-27 09:55:57', 'beer'),
(25, 'Jameson Black', '700000.00', 'available', 'uploads/6746f282eb360-jamesonBlack.png', '2024-11-27 10:20:50', '2024-11-27 10:20:50', 'whiskey');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `login_id` (`login_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `login_id` (`login_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`login_id`) REFERENCES `login` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`login_id`) REFERENCES `login` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
