<?php
require_once 'function.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Panggil fungsi loginUser untuk memeriksa kredensial
    loginUser($username, $password);
}

// Cek jika permintaan POST untuk logout dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <!-- Tampilkan pesan flash jika ada -->
        <?php

        if (isset($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $category => $message) {
                echo "<div class='flash-message flash-$category'>$message</div>";
            }
            unset($_SESSION['flash_messages']);
        }
        ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" id="username" placeholder="Masukkan username" required>
            <input type="password" name="password" id="password" placeholder="Masukkan password" required>
            <button type="submit">Login</button>
        </form>

        <!-- Link ke halaman register -->
        <div class="register-link">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>

        <div class="footer">© 2024 Web Kelompok 6</div>
    </div>
</body>
</html>
