<?php
// function.php
session_start();
require_once 'connect.php';


//FUNGSI LOGIN
function loginUser($username, $password)
{
    global $pdo;

    // Query untuk mengecek pengguna berdasarkan username
    $query = "SELECT * FROM login WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    // Ambil hasilnya
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil, simpan username dan role pengguna di session
        $_SESSION['user'] = [
            'login_id' => $user['id'], // Simpan ID pengguna (dari tabel login)
            'username' => $user['username'],
            'role' => $user['role']
        ];

        // Tentukan halaman tujuan berdasarkan role
        if ($user['role'] === 'admin') {
            $redirectUrl = 'admin.php'; // Halaman untuk admin
        } elseif ($user['role'] === 'user') {
            $redirectUrl = 'home.php'; // Halaman untuk user (langsung ke shop)
        } else {
            // Jika role tidak dikenal, arahkan ke halaman default
            $redirectUrl = 'login.php';
        }

        // Pesan flash untuk login berhasil
        $_SESSION['flash_messages']['success'] = "Login berhasil! Selamat datang, {$user['username']}.";
        header("Location: $redirectUrl");
        exit;
    } else {
        // Login gagal
        $_SESSION['flash_messages']['danger'] = "Username atau password salah.";
        header("Location: login.php"); // Kembali ke halaman login
        exit;
    }
}


// FUNGSI REGISTER UNTUK MENDAFTARKAN AKUN
function registerUser($username, $password, $role)
{
    global $pdo;

    // Cek apakah username sudah digunakan
    $query = "SELECT * FROM login WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash_messages']['danger'] = "Username sudah terdaftar.";
        header("Location: register.php");
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan pengguna baru
    $query = "INSERT INTO login (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->bindParam(":role", $role);

    if ($stmt->execute()) {
        $_SESSION['flash_messages']['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['flash_messages']['danger'] = "Registrasi gagal. Silakan coba lagi.";
        header("Location: register.php");
        exit;
    }
}

// SESSION DESTROY
function logoutUser()
{
    // Hapus semua data session
    session_unset();
    session_destroy();
    // Redirect ke halaman login
    header("Location: login.php");
    exit;
}

// FUNGSI MANAGE PRODUCTS ADMIN
// FUNGSI TAMBAH PRODUK
function addProduct($name, $price, $status, $category, $image)
{
    global $pdo;
    try {
        $id = getAvailableId($pdo);
        $imagePath = null;

        // Upload gambar jika ada
        if (!empty($image['name'])) {
            $imageName = uniqid() . '-' . basename($image['name']);
            $targetDirectory = 'uploads/';
            $targetFile = $targetDirectory . $imageName;

            if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
                throw new Exception("Gagal mengupload gambar.");
            }
            $imagePath = $targetFile;
        }

        // Simpan produk ke database
        $stmt = $pdo->prepare("INSERT INTO products (id, name, price, status, category, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $name, $price, $status, $category, $imagePath]);

        $_SESSION['flash_messages']['success'] = "Produk berhasil ditambahkan!";
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal menambahkan produk: " . $e->getMessage();
    }
}

// FUNGSI EDIT PRODUK
function editProduct($id, $name, $price, $status, $category, $image, $existingImage)
{
    global $pdo;
    try {
        $imagePath = $existingImage;

        // Upload gambar baru jika ada
        if (!empty($image['name'])) {
            $imageName = uniqid() . '-' . basename($image['name']);
            $targetDirectory = 'uploads/';
            $targetFile = $targetDirectory . $imageName;

            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;

                // Hapus gambar lama jika ada
                if ($existingImage && file_exists($existingImage)) {
                    unlink($existingImage);
                }
            }
        }

        // Update produk di database
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, status = ?, category = ?, image_path = ? WHERE id = ?");
        $stmt->execute([$name, $price, $status, $category, $imagePath, $id]);

        $_SESSION['flash_messages']['success'] = "Produk berhasil diperbarui!";
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal memperbarui produk: " . $e->getMessage();
    }
}

// FUNGSI HAPUS PRODUK
function deleteProduct($id)
{
    global $pdo;
    try {
        // Hapus gambar dari server jika ada
        $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }

        // Hapus produk dari database
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['flash_messages']['success'] = "Produk berhasil dihapus!";
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal menghapus produk: " . $e->getMessage();
    }
}

//FUNGSI UNTUK MENAMPILKAN PRODUCT DI SHOP USER
//FUNGSI AMBIL SEMUA PRODUK
function getProducts()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// FUNGSI AMBIL PRODUK BERDASARKAN KATEGORI
function getProductsGroupedByCategory()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY category, name");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];
    foreach ($products as $product) {
        $grouped[$product['category']][] = $product;
    }
    return $grouped;
}

// FUNGSI UNTUK MENGAMBIL ID YANG TERSEDIA
function getAvailableId($pdo)
{
    $stmt = $pdo->query("SELECT MAX(id) + 1 AS next_id FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['next_id'] ?? 1; // Jika tabel kosong, mulai dari ID 1
}

//FUNGSI UNTUK MENAMBAHKAN PRODUCTS KE KERANJANG DARI SHOP USER
function addToCart($loginId, $productId, $quantity)
{
    global $pdo; // Gunakan koneksi PDO dari connect.php

    // Periksa apakah barang sudah ada di keranjang
    $query = "SELECT quantity FROM cart WHERE login_id = :login_id AND product_id = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['login_id' => $loginId, 'product_id' => $productId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Jika barang ada, tambahkan jumlahnya
        $query = "UPDATE cart SET quantity = quantity + :quantity WHERE login_id = :login_id AND product_id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'quantity' => $quantity,
            'login_id' => $loginId,
            'product_id' => $productId
        ]);
    } else {
        // Jika barang belum ada, tambahkan entri baru
        $query = "INSERT INTO cart (login_id, product_id, quantity) VALUES (:login_id, :product_id, :quantity)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'login_id' => $loginId,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }
}

//FUNGSI MENGAMBIL ITEM DI KERANJANG
function getCartItems($loginId)
{
    global $pdo;
    try {
        $query = "SELECT c.cart_id, c.product_id, c.quantity, p.name AS product_name, p.price, p.image_path 
                  FROM cart c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.login_id = :login_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':login_id' => $loginId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal mengambil keranjang: " . $e->getMessage();
        return [];
    }
}


function clearCart($loginId)
{
    global $pdo;
    try {
        $query = "DELETE FROM cart WHERE login_id = :login_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':login_id' => $loginId]);
        $_SESSION['flash_messages']['success'] = "Checkout berhasil. Keranjang telah dikosongkan!";
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal mengosongkan keranjang: " . $e->getMessage();
    }
}

//FUNGSI MENGHAPUS PRODUCTS YANG ADA DI KERANJANG
function removeCartItem($cartId)
{
    global $pdo;
    try {
        // Hapus item berdasarkan cart_id
        $query = "DELETE FROM cart WHERE cart_id = :cart_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':cart_id' => $cartId]);
        $_SESSION['flash_messages']['success'] = "Item berhasil dihapus dari keranjang.";
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal menghapus item dari keranjang: " . $e->getMessage();
    }
}

function updateCartQuantity($cartId, $quantity)
{
    global $pdo;
    try {
        // Update jumlah produk berdasarkan cart_id
        $query = "UPDATE cart SET quantity = :quantity WHERE cart_id = :cart_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':cart_id' => $cartId,
            ':quantity' => $quantity
        ]);
        $_SESSION['flash_messages']['success'] = "Jumlah produk berhasil diperbarui.";
    } catch (Exception $e) {
        $_SESSION['flash_messages']['danger'] = "Gagal memperbarui jumlah produk: " . $e->getMessage();
    }
}

//FUNGSI UNTUK MELAKUKAN CHECOUT PRODUCTS
function checkout($loginId, $totalPrice)
{
    global $pdo;

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Masukkan data ke tabel orders
        $query = "INSERT INTO orders (login_id, total_price, status) VALUES (:login_id, :total_price, 'Pending')";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'login_id' => $loginId,
            'total_price' => $totalPrice
        ]);

        // Ambil ID pesanan terakhir
        $orderId = $pdo->lastInsertId();

        // Ambil item dari keranjang
        $cartItems = getCartItems($loginId);

        // Simpan detail pesanan ke tabel order_items
        foreach ($cartItems as $item) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Hapus semua item di keranjang pengguna
        $query = "DELETE FROM cart WHERE login_id = :login_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['login_id' => $loginId]);

        // Commit transaksi
        $pdo->commit();

        return $orderId; // Kembalikan ID pesanan
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e; // Tangani error jika terjadi
    }
}

//UFNGSI AMBIL DETAIL PESANAN DARI KERANJANG
function getOrderDetails($orderId)
{
    global $pdo;

    $query = "SELECT oi.order_id, oi.product_id, oi.quantity, oi.price, p.name AS product_name
              FROM order_items oi
              JOIN products p ON oi.product_id = p.id
              WHERE oi.order_id = :order_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['order_id' => $orderId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//FUNGSI AMBIL NAMA USER YANG MELAKUKAN PROSES CHECKOUT
function getUserOrders($loginId)
{
    global $pdo;

    $query = "SELECT * FROM orders WHERE login_id = :login_id ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['login_id' => $loginId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//FUNGSI PESANAN TERTUNDA UNTUK TAMPILAN USER
function getPendingOrders() {
    global $pdo;

    try {
        $query = "SELECT o.order_id, o.total_price, o.created_at, l.username
                  FROM orders o
                  JOIN login l ON o.login_id = l.id
                  WHERE o.status = :status";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['status' => 'Pending']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

//FUNGSI PESANAN DIKONFIRMASI UNTUK TAMPILAN USER
function confirmOrder($orderId)
{
    global $pdo;

    $query = "UPDATE orders SET status = 'Confirmed' WHERE order_id = :order_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['order_id' => $orderId]);
}

//FUNGSI MENAMPILKAN TOTAL PRODUK YANG DITAMBAHKAN OLEH ADMIN
function getTotalProducts()
{
    global $pdo;

    $query = "SELECT COUNT(*) AS total FROM products";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] ?? 0; // Jika tidak ada data, kembalikan 0
}

//FUNGSI TOTAL PESANAN TERTUNDA UNTUK TAMPILAN ADMIN
function getPendingPayments()
{
    global $pdo;

    $query = "SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] ?? 0;
}

//FUNGSI RIWAYAT PESANAN USER UNTUK TAMPILAN ADMIN
function getAllOrderHistory()
{
    global $pdo;

    $query = "
        SELECT 
            o.order_id, 
            o.total_price, 
            o.status, 
            o.created_at, 
            l.username,
            GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') AS items
        FROM orders o
        JOIN login l ON o.login_id = l.id
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        GROUP BY o.order_id
        ORDER BY o.created_at DESC";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//FUNGSI PESNAN DIKONFIRMASI UNTUK TAMPILAN ADMIN
function getConfirmedPayments()
{
    global $pdo;

    $query = "SELECT COUNT(*) AS total FROM orders WHERE status = 'Confirmed'";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] ?? 0; // Jika tidak ada data, kembalikan 0
}

