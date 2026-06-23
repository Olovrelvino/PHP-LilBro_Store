    <?php
    require_once 'function.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Panggil fungsi untuk menyimpan pengguna baru
        registerUser($username, $password, $role);
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>Register</h1>

            <!-- Tampilkan pesan flash jika ada -->
            <?php
            if (isset($_SESSION['flash_messages'])) {
                foreach ($_SESSION['flash_messages'] as $category => $message) {
                    echo "<div class='flash-message flash-$category'>$message</div>";
                }
                unset($_SESSION['flash_messages']);
            }
            ?>

            <form action="register.php" method="POST">
                <input type="text" name="username" id="username" placeholder="Masukkan username" required>
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit">Register</button>
            </form>

            <!-- Link ke halaman login -->
            <div class="login-link">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>

            <div class="footer">© 2024 Web Kelompok 6</div>
        </div>
    </body>
    </html>
