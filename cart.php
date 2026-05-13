<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

if (isset($_GET['remove'])) {
    $rid = (int)$_GET['remove'];
    unset($_SESSION['cart'][$rid]);
    header("Location: /shoe-store-main/cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $pid => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) unset($_SESSION['cart'][$pid]);
        else $_SESSION['cart'][$pid] = $qty;
    }
    header("Location: /shoe-store-main/cart.php");
    exit;
}

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

<div class="page-wrap">
    <h2 class="page-title">Shopping Cart</h2>

    <?php if (empty($products)): ?>
        <div style="text-align:center;padding:80px 0;">
            <div style="font-size:64px;margin-bottom:16px;">🛒</div>
            <p style="font-size:18px;color:var(--gray-400);margin-bottom:24px;">Your cart is empty!</p>
            <a href="/shoe-store-main/" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="POST">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="<?= htmlspecialchars($p['image_url']) ?>"
                                 style="width:64px;height:64px;object-fit:cover;background:var(--gray-100);">
                            <div>
                                <div style="font-weight:500"><?= htmlspecialchars($p['name']) ?></div>
                                <div style="font-size:12px;color:var(--gray-400)"><?= htmlspecialchars($p['category']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= number_format($p['price'], 0, ',', '.') ?>đ</td>
                    <td>
                        <input type="number" name="quantity[<?= $p['id'] ?>]"
                               value="<?= $cart[$p['id']] ?>"
                               min="0" max="<?= $p['stock'] ?>"
                               class="qty-input">
                    </td>
                    <td style="font-weight:500"><?= number_format($p['price'] * $cart[$p['id']], 0, ',', '.') ?>đ</td>
                    <td>
                        <a href="/shoe-store-main/cart.php?remove=<?= $p['id'] ?>"
                           style="color:var(--red);font-size:18px;">✕</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align:right;margin-top:16px;">
            <button type="submit" class="btn btn-outline">Update Cart</button>
        </div>
        </form>

        <div class="cart-summary">
            <div>
                <div style="font-size:13px;color:var(--gray-400);letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">Total</div>
                <div style="font-family:var(--font-display);font-size:32px;"><?= number_format($total, 0, ',', '.') ?>đ</div>
            </div>
            <a href="/shoe-store-main/checkout.php" class="btn-primary">
                Checkout →
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>