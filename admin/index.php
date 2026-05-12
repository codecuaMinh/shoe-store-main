<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /shoe-store-main/admin/login.php");
    exit;
}
require_once '../includes/db.php';

$total_orders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue  = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'paid'")->fetchColumn() ?? 0;
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$low_stock      = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 10")->fetchColumn();
$recent_orders  = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$products       = $pdo->query("SELECT * FROM products ORDER BY stock ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
    <link rel="stylesheet" href="/shoe-store-main/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">👟 Admin</div>
        <nav>
            <a href="/shoe-store-main/admin/" class="active">📊 Dashboard</a>
            <a href="/shoe-store-main/admin/products.php">👟 Products</a>
            <a href="/shoe-store-main/admin/orders.php">📦 Orders</a>
            <a href="/shoe-store-main/" target="_blank">🌐 View Shop</a>
            <a href="/shoe-store-main/admin/logout.php">🚪 Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <span style="font-family:var(--font-mono);font-size:12px;color:var(--gray-400)"><?= date('d/m/Y') ?></span>
        </div>
        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Total Orders</div>
                <div class="metric-value"><?= $total_orders ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Revenue</div>
                <div class="metric-value" style="font-size:22px"><?= number_format($total_revenue, 0, ',', '.') ?>đ</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Products</div>
                <div class="metric-value"><?= $total_products ?></div>
            </div>
            <div class="metric-card" style="border-left:3px solid #d94f3d">
                <div class="metric-label">Low Stock</div>
                <div class="metric-value" style="color:#d94f3d"><?= $low_stock ?></div>
            </div>
        </div>
        <div class="admin-section">
            <h2>Recent Orders</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th><th>Customer</th><th>Phone</th>
                        <th>Total</th><th>Status</th><th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['customer_name']) ?></td>
                        <td><?= htmlspecialchars($o['phone']) ?></td>
                        <td><?= number_format($o['total'], 0, ',', '.') ?>đ</td>
                        <td><span class="badge badge-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
                        <td style="font-size:12px"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="admin-section">
            <h2>Inventory Status</h2>
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= number_format($p['price'], 0, ',', '.') ?>đ</td>
                        <td style="<?= $p['stock'] < 10 ? 'color:#d94f3d;font-weight:600' : '' ?>"><?= $p['stock'] ?></td>
                        <td><span class="badge <?= $p['stock'] < 10 ? 'badge-pending' : 'badge-paid' ?>"><?= $p['stock'] < 10 ? 'Low Stock' : 'In Stock' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>