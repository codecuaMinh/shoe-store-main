<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

define('VNP_HASHSECRET', 'DD51961KXQRFBBM7JHMDX3FZ9XXWH4U0'); // Thay bằng vnp_HashSecret trong email

$vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
$inputData = [];

foreach ($_GET as $key => $value) {
    if (str_starts_with($key, 'vnp_') && $key !== 'vnp_SecureHash') {
        $inputData[$key] = $value;
    }
}

ksort($inputData);
$query    = http_build_query($inputData);
$myHash   = hash_hmac('sha512', $query, VNP_HASHSECRET);
$success  = ($myHash === $vnp_SecureHash && $_GET['vnp_ResponseCode'] === '00');

// Cập nhật trạng thái đơn hàng và thanh toán
if ($success) {
    $txnRef  = $_GET['vnp_TxnRef'];
    $orderId = explode('_', $txnRef)[0];

    $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?")->execute([$orderId]);
    $pdo->prepare("UPDATE payments SET status = 'success' WHERE vnp_txn_ref = ?")->execute([$txnRef]);

    // Xóa giỏ hàng
    unset($_SESSION['cart']);
    unset($_SESSION['order_id']);
    unset($_SESSION['order_total']);
}
?>

<div class="container" style="text-align:center;padding:80px 20px;">
    <?php if ($success): ?>
        <div style="font-size:64px;margin-bottom:16px;">✅</div>
        <h2 style="font-size:28px;margin-bottom:12px;">Thanh toán thành công!</h2>
        <p style="color:#888;margin-bottom:32px;">Đơn hàng của bạn đã được xác nhận. Cảm ơn bạn đã mua hàng!</p>
        <a href="/shoe-store/" class="btn">Tiếp tục mua sắm</a>
    <?php else: ?>
        <div style="font-size:64px;margin-bottom:16px;">❌</div>
        <h2 style="font-size:28px;margin-bottom:12px;">Thanh toán thất bại!</h2>
        <p style="color:#888;margin-bottom:32px;">Đã có lỗi xảy ra. Vui lòng thử lại.</p>
        <a href="/shoe-store/cart.php" class="btn">Quay lại giỏ hàng</a>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>