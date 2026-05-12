<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /shoe-store-main/admin/login.php");
    exit;
}
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $name      = htmlspecialchars($_POST['name']);
    $price     = (float)$_POST['price'];
    $stock     = (int)$_POST['stock'];
    $category  = htmlspecialchars($_POST['category']);
    $image_url = htmlspecialchars($_POST['image_url']);

    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, category, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $stock, $category, $image_url]);
    } elseif ($_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, stock=?, category=?, image_url=? WHERE id=?");
        $stmt->execute([$name, $price, $stock, $category, $image_url, $id]);
    }
    header("Location: products.php");
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM products WHERE id=?")->execute([(int)$_GET['delete']]);
    header("Location: products.php");
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management – Admin</title>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
    <link rel="stylesheet" href="/shoe-store-main/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">👟 Admin</div>
        <nav>
            <a href="/shoe-store-main/admin/">📊 Dashboard</a>
            <a href="/shoe-store-main/admin/products.php" class="active">👟 Products</a>
            <a href="/shoe-store-main/admin/orders.php">📦 Orders</a>
            <a href="/shoe-store-main/" target="_blank">🌐 View Shop</a>
            <a href="/shoe-store-main/admin/logout.php">🚪 Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-header">
            <h1><?= $edit ? 'Edit Product' : 'Product Management' ?></h1>
        </div>
        <div class="admin-section" style="margin-bottom:24px;">
            <h2><?= $edit ? 'Edit Product' : 'Add New Product' ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
                <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>
                <div class="admin-form">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required value="<?= $edit ? htmlspecialchars($edit['name']) : '' ?>" placeholder="Nike Air Force 1">
                    </div>
                    <div class="form-group">
                        <label>Price (VND)</label>
                        <input type="number" name="price" required value="<?= $edit ? $edit['price'] : '' ?>" placeholder="2500000">
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" required value="<?= $edit ? $edit['stock'] : '' ?>" placeholder="50">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <?php foreach (['Sneaker','Skate','Lifestyle','Running','Formal'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= ($edit && $edit['category'] === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label>Image URL</label>
                        <input type="text" name="image_url" value="<?= $edit ? htmlspecialchars($edit['image_url']) : '' ?>" placeholder="https://...">
                    </div>
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn"><?= $edit ? 'Update Product' : 'Add Product' ?></button>
                    <?php if ($edit): ?>
                    <a href="products.php" class="btn btn-outline">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="admin-section">
            <h2>All Products (<?= count($products) ?>)</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th><th>Name</th><th>Category</th>
                        <th>Price</th><th>Stock</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>#<?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= number_format($p['price'], 0, ',', '.') ?>đ</td>
                        <td style="<?= $p['stock'] < 10 ? 'color:#d94f3d;font-weight:600' : '' ?>"><?= $p['stock'] ?></td>
                        <td><span class="badge <?= $p['stock'] < 10 ? 'badge-pending' : 'badge-paid' ?>"><?= $p['stock'] < 10 ? 'Low Stock' : 'In Stock' ?></span></td>
                        <td style="display:flex;gap:6px;">
                            <a href="products.php?edit=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                            <a href="products.php?delete=<?= $p['id'] ?>" class="btn-danger"
                               onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>