<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

// Xử lý xóa sản phẩm
if (isset($_GET['remove'])) {
    $rid = (int)$_GET['remove'];
    unset($_SESSION['cart'][$rid]);
    header("Location: cart.php");
    exit;
}

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $pid => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) unset($_SESSION['cart'][$pid]);
        else $_SESSION['cart'][$pid] = $qty;
    }
    header("Location: cart.php");
    exit;
}

// Lấy thông tin sản phẩm trong giỏ
$cart = $_SESSION['cart'] ?? [];
$products = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $p) {
        $total += $p['price'] * $cart[$p['id']];
    }
}
?>

<div class="container">
    <h2 style="margin-bottom:24px;">🛒 Giỏ hàng</h2>

    <?php if (empty($products)): ?>
        <div style="text-align:center;padding:60px 0;color:#888;">
            <p style="font-size:18px;margin-bottom:16px;">Giỏ hàng trống!</p>
            <a href="/shoe-store/" class="btn">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form method="POST">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                                 style="width:60px;height:60px;object-fit:cover;border-radius:8px;background:#f0f0f0;">
                            <span><?= htmlspecialchars($p['name']) ?></span>
                        </div>
                    </td>
                    <td><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
                    <td>
                        <input type="number" name="quantity[<?= $p['id'] ?>]" 
                               value="<?= $cart[$p['id']] ?>" 
                               min="0" max="<?= $p['stock'] ?>"
                               style="width:60px;padding:6px;border:1.5px solid #ddd;border-radius:6px;text-align:center;">
                    </td>
                    <td><?= number_format($p['price'] * $cart[$p['id']], 0, ',', '.') ?> đ</td>
                    <td>
                        <a href="cart.php?remove=<?= $p['id'] ?>" 
                           style="color:#e74c3c;text-decoration:none;font-size:20px;">✕</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align:right;margin-top:16px;">
            <button type="submit" class="btn btn-outline">Cập nhật giỏ hàng</button>
        </div>
        </form>

        <div class="cart-summary">
            <div style="font-size:18px;">Tổng cộng: 
                <strong style="font-size:24px;"><?= number_format($total, 0, ',', '.') ?> đ</strong>
            </div>
            <a href="/shoe-store/checkout.php" class="btn" style="padding:14px 32px;font-size:16px;">
                Thanh toán →
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>