<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    echo "<div class='page-wrap'><p>Product not found.</p></div>";
    require_once 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = (int)$_POST['quantity'];
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header("Location: /shoe-store-main/cart.php");
    exit;
}
?>

<a href="/shoe-store-main/" class="back-link" style="display:inline-flex;align-items:center;gap:6px;padding:16px 48px;font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--gray-600);">
    ← Back
</a>

<div class="product-detail">
    <div class="detail-image-wrap">
        <img src="<?= htmlspecialchars($p['image_url']) ?>"
             alt="<?= htmlspecialchars($p['name']) ?>"
             onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500'">
    </div>
    <div class="detail-info">
        <div class="detail-brand"><?= htmlspecialchars($p['category']) ?></div>
        <div class="detail-name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="detail-price"><?= number_format($p['price'], 0, ',', '.') ?>đ</div>
        <div class="detail-stock">
            <?= $p['stock'] < 10 ? '🔥 Only '.$p['stock'].' pairs left' : '✓ In Stock — '.$p['stock'].' pairs' ?>
        </div>

        <form method="POST">
            <div class="qty-row">
                <span class="qty-label">Quantity</span>
                <input type="number" name="quantity" value="1" min="1"
                       max="<?= $p['stock'] ?>" class="qty-input">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;padding:16px;font-size:14px;">
                Add to Cart →
            </button>
        </form>

        <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--gray-200);">
            <div style="font-size:12px;color:var(--gray-400);line-height:2;">
                <div>✓ Free shipping on orders over 500K</div>
                <div>✓ 30-day easy returns</div>
                <div>✓ 100% authentic products</div>
                <div>🔒 Secure payment via VNPay</div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>