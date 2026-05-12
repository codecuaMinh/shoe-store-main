<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /shoe-store-main/admin/login.php");
    exit;
}
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->execute([$_POST['status'], (int)$_POST['order_id']]);
    header("Location: orders.php");
    exit;
}

$filter = $_GET['status'] ?? 'all';
$where  = $filter !== 'all' ? "WHERE status = '$filter'" : '';
$orders = $pdo->query("SELECT * FROM orders $where ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$statusLabels = [
    'pending'  => 'Pending',
    'paid'     => 'Paid',
    'shipping' => 'Shipping',
    'done'     => 'Done',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management – Admin</title>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
    <link rel="stylesheet" href="/shoe-store-main/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">👟 Admin</div>
        <nav>
            <a href="/shoe-store-main/admin/">📊 Dashboard</a>
            <a href="/shoe-store-main/admin/products.php">👟 Products</a>
            <a href="/shoe-store-main/admin/orders.php" class="active">📦 Orders</a>
            <a href="/shoe-store-main/" target="_blank">🌐 View Shop</a>
            <a href="/shoe-store-main/admin/logout.php">🚪 Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-header">
            <h1>Order Management</h1>
        </div>
        <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
            <?php foreach (['all'=>'All','pending'=>'Pending','paid'=>'Paid','shipping'=>'Shipping','done'=>'Done'] as $val => $label): ?>
            <a href="orders.php?status=<?= $val ?>"
               class="btn <?= $filter === $val ? '' : 'btn-outline' ?>"
               style="padding:6px 16px;font-size:12px;">
                <?= $label ?>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="admin-section">
            <h2>Orders (<?= count($orders) ?>)</h2>
            <?php if (empty($orders)): ?>
            <p style="color:var(--gray-400);padding:20px 0;">No orders found.</p>
            <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th><th>Customer</th><th>Phone</th>
                        <th>Address</th><th>Total</th><th>Status</th>
                        <th>Date</th><th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['customer_name']) ?></td>
                        <td><?= htmlspecialchars($o['phone']) ?></td>
                        <td style="max-width:140px;font-size:12px;"><?= htmlspecialchars($o['address']) ?></td>
                        <td><?= number_format($o['total'], 0, ',', '.') ?>đ</td>
                        <td><span class="badge badge-<?= $o['status'] ?>"><?= $statusLabels[$o['status']] ?? $o['status'] ?></span></td>
                        <td style="font-size:12px;"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                        <td>
                            <?php if ($o['status'] !== 'done'): ?>
                            <form method="POST" style="display:flex;gap:6px;align-items:center;">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="status" style="font-size:12px;padding:4px 6px;border:1px solid #ddd;border-radius:4px;background:#fff;">
                                    <?php foreach ($statusLabels as $val => $label): ?>
                                    <?php if ($val !== $o['status']): ?>
                                    <option value="<?= $val ?>"><?= $label ?></option>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm">Save</button>
                            </form>
                            <?php else: ?>
                            <span style="color:var(--gray-400);font-size:12px;">Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr style="background:var(--gray-100);">
                        <td colspan="8" style="padding:8px 16px;">
                            <?php
                            $items = $pdo->prepare("SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                            $items->execute([$o['id']]);
                            foreach ($items->fetchAll(PDO::FETCH_ASSOC) as $item):
                            ?>
                            <span style="font-size:12px;color:var(--gray-600);margin-right:16px;">
                                📦 <?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?>
                                — <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                            </span>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>