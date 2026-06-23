<?php
require_once 'function.php';

// Pastikan hanya admin yang dapat mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['flash_messages']['danger'] = "Akses ditolak! Halaman ini hanya bisa diakses oleh admin.";
    header("Location: login.php");
    exit;
}

// Proses Tambah Produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $status = $_POST['status'];
        $category = $_POST['category'];
        $image = $_FILES['image'];

        addProduct($name, $price, $status, $category, $image);
    }

    if (isset($_POST['edit_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $status = $_POST['status'];
        $category = $_POST['category'];
        $image = $_FILES['image'];
        $existingImage = $_POST['existing_image'];

        editProduct($id, $name, $price, $status, $category, $image, $existingImage);
    }
}

// Proses Hapus Produk
if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    deleteProduct($id);
}

// Cek jika tombol logout ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
}

// Ambil semua produk untuk ditampilkan di tabel
$products = getProducts();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Manage Products</title>
    <link rel="stylesheet" href="css/addProduct.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2><i>LiLBRO</i> Admin</h2>
            <ul class="menu">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="addProduct.php" class="active">Manage Products</a></li>
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
            <section id="add-product">
                <header>
                    <h1>List Products</h1>
                    <button id="addProductButton" class="button">Tambah Produk</button>
                </header>

                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td>Rp <?= number_format($product['price'], 2, ',', '.') ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $product['status'])) ?></td>
                                <td><?= ucfirst($product['category']) ?></td>
                                <td>
                                    <?php if ($product['image_path']): ?>
                                        <img src="<?= $product['image_path'] ?>" alt="<?= $product['name'] ?>" width="100">
                                    <?php else: ?>
                                        Tidak ada gambar
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn-edit" onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)">Edit</button>
                                    <a href="addProduct.php?delete_product=<?= $product['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="btn-delete">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Modal Tambah Produk -->
                <div id="addProductModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('addProductModal')">&times;</span>
                        <h2>Tambah Produk</h2>
                        <form id="addProductForm" method="POST" enctype="multipart/form-data">
                            <input type="text" name="name" placeholder="Nama Produk" required>
                            <input type="number" name="price" placeholder="Harga Produk" required>
                            <select name="status" required>
                                <option value="available">Available</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                            <select name="category" required>
                                <option value="beer">Beer</option>
                                <option value="whiskey">Whiskey</option>
                                <option value="vodka">Vodka</option>
                                <option value="local_products">Local Products</option>
                                <option value="import_products">Import Products</option>
                            </select>
                            <input type="file" name="image" accept="image/*">
                            <button type="submit" name="add_product" class="button">Tambah Produk</button>
                        </form>
                    </div>
                </div>

                <!-- Modal Edit Produk -->
                <div id="editProductModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('editProductModal')">&times;</span>
                        <h2>Edit Produk</h2>
                        <form id="editProductForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="edit-id" name="id">
                            <input type="text" id="edit-name" name="name" placeholder="Nama Produk" required>
                            <input type="number" id="edit-price" name="price" placeholder="Harga Produk" required>
                            <select id="edit-status" name="status" required>
                                <option value="available">Available</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                            <select id="edit-category" name="category" required>
                                <option value="beer">Beer</option>
                                <option value="whiskey">Whiskey</option>
                                <option value="vodka">Vodka</option>
                                <option value="local_products">Local Products</option>
                                <option value="import_products">Import Products</option>
                            </select>
                            <input type="file" name="image" accept="image/*">
                            <input type="hidden" id="existing-image" name="existing_image">
                            <button type="submit" name="edit_product" class="button">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        document.getElementById('addProductButton').addEventListener('click', function() {
            openModal('addProductModal');
        });

        function editProduct(product) {
            document.getElementById('edit-id').value = product.id;
            document.getElementById('edit-name').value = product.name;
            document.getElementById('edit-price').value = product.price;
            document.getElementById('edit-status').value = product.status;
            document.getElementById('edit-category').value = product.category;
            document.getElementById('existing-image').value = product.image_path;
            openModal('editProductModal');
        }
    </script>
</body>

</html>