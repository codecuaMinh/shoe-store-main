<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    echo "<div class='container'><p>Sản phẩm không tồn tại.</p></div>";
    require_once 'includes/footer.php';
    exit;
}

// Xử lý thêm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = (int)$_POST['quantity'];
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header("Location: cart.php");
    exit;
}
?>

<div class="container">
    <a href="/shoe-store/" style="color:#888;text-decoration:none;font-size:14px;">← Quay lại</a>
    <div class="product-detail">
        <div class="detail-image">
            <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 onerror="this.src='https://via.placeholder.com/500x400?text=No+Image'">
        </div>
        <div class="detail-info">
            <span class="product-category"><?= htmlspecialchars($p['category']) ?></span>
            <h1><?= htmlspecialchars($p['name']) ?></h1>
            <div class="detail-price"><?= number_format($p['price'], 0, ',', '.') ?> đ</div>
            <div class="detail-stock">Còn lại: <?= $p['stock'] ?> đôi</div>

            <form method="POST">
                <div class="qty-selector">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1" max="<?= $p['stock'] ?>">
                </div>
                <button type="submit" class="btn" style="width:100%;padding:14px;font-size:16px;margin-top:16px;">
                    🛒 Thêm vào giỏ hàng
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>