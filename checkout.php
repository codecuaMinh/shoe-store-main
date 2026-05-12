<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

// Nếu giỏ hàng trống thì redirect về trang chủ
if (empty($_SESSION['cart'])) {
    header("Location: /shoe-store/");
    exit;
}

// Lấy thông tin sản phẩm trong giỏ
$cart = $_SESSION['cart'];
$ids = implode(',', array_map('intval', array_keys($cart)));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($products as $p) {
    $total += $p['price'] * $cart[$p['id']];
}

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = htmlspecialchars($_POST['name']);
    $phone   = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    // Tạo đơn hàng
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, total, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$name, $phone, $address, $total]);
    $order_id = $pdo->lastInsertId();

    // Lưu chi tiết đơn hàng + trừ kho
    foreach ($products as $p) {
        $qty = $cart[$p['id']];
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $p['id'], $qty, $p['price']]);

        // Trừ tồn kho (Data Integration)
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$qty, $p['id']]);
    }

    // Lưu session order_id để VNPay dùng
    $_SESSION['order_id'] = $order_id;
    $_SESSION['order_total'] = $total;

    // Redirect sang VNPay
    header("Location: /shoe-store/api/vnpay.php");
    exit;
}
?>

<div class="container">
    <h2 style="margin-bottom:24px;">Thanh toán</h2>
    <div class="checkout-grid">

        <!-- Form thông tin -->
        <div class="checkout-form">
            <h3 style="margin-bottom:20px;">Thông tin giao hàng</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="name" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" placeholder="0901234567" required>
                </div>
                <div class="form-group">
                    <label>Địa chỉ giao hàng</label>
                    <textarea name="address" rows="3" placeholder="Số nhà, đường, phường, quận, tỉnh..." required></textarea>
                </div>
                <button type="submit" class="btn" style="width:100%;padding:14px;font-size:16px;">
                    Thanh toán qua VNPay →
                </button>
            </form>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="order-summary">
            <h3 style="margin-bottom:20px;">Đơn hàng của bạn</h3>
            <?php foreach ($products as $p): ?>
            <div class="summary-item">
                <span><?= htmlspecialchars($p['name']) ?> x<?= $cart[$p['id']] ?></span>
                <span><?= number_format($p['price'] * $cart[$p['id']], 0, ',', '.') ?> đ</span>
            </div>
            <?php endforeach; ?>
            <div class="summary-total">
                <span>Tổng cộng</span>
                <strong><?= number_format($total, 0, ',', '.') ?> đ</strong>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>