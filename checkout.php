<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

if (empty($_SESSION['cart'])) {
    header("Location: /shoe-store-main/");
    exit;
}

$cart = $_SESSION['cart'];
$ids = implode(',', array_map('intval', array_keys($cart)));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($products as $p) {
    $total += $p['price'] * $cart[$p['id']];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = htmlspecialchars($_POST['name']);
    $phone   = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, total, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$name, $phone, $address, $total]);
    $order_id = $pdo->lastInsertId();

    foreach ($products as $p) {
        $qty = $cart[$p['id']];
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $p['id'], $qty, $p['price']]);
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$qty, $p['id']]);
    }

    $_SESSION['order_id'] = $order_id;
    $_SESSION['order_total'] = $total;

    header("Location: /shoe-store-main/api/vnpay.php");
    exit;
}
?>

<div class="page-wrap">
    <a href="/shoe-store-main/cart.php" class="back-link">← Back to Cart</a>
    <h1 class="page-title">Checkout</h1>
    <div class="checkout-grid">
        <div class="checkout-form">
            <h3 style="font-family:var(--font-display);font-size:24px;letter-spacing:1px;margin-bottom:24px;">Shipping Information</h3>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Nguyen Van A" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" placeholder="0901234567" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery Address</label>
                    <textarea name="address" class="form-input" rows="3" placeholder="House number, street, ward, district, city..." required style="resize:vertical"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;padding:16px;font-size:14px;margin-top:8px;">
                    Pay via VNPay →
                </button>
            </form>
        </div>
        <div class="order-summary">
            <h3 style="font-family:var(--font-display);font-size:24px;letter-spacing:1px;margin-bottom:24px;">Order Summary</h3>
            <?php foreach ($products as $p): ?>
            <div class="summary-item">
                <span><?= htmlspecialchars($p['name']) ?> x<?= $cart[$p['id']] ?></span>
                <span><?= number_format($p['price'] * $cart[$p['id']], 0, ',', '.') ?>đ</span>
            </div>
            <?php endforeach; ?>
            <div class="summary-total">
                <span>Total</span>
                <strong><?= number_format($total, 0, ',', '.') ?>đ</strong>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>