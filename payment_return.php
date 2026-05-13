<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

define('VNP_HASHSECRET', 'DD51961KXQRFBBM7JHMDX3FZ9XXWH4U0');

$vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
$inputData = [];

foreach ($_GET as $key => $value) {
    if (str_starts_with($key, 'vnp_') && $key !== 'vnp_SecureHash') {
        $inputData[$key] = $value;
    }
}

ksort($inputData);
$query   = http_build_query($inputData);
$myHash  = hash_hmac('sha512', $query, VNP_HASHSECRET);
$success = ($myHash === $vnp_SecureHash && ($_GET['vnp_ResponseCode'] ?? '') === '00');

if ($success) {
    $txnRef  = $_GET['vnp_TxnRef'];
    $orderId = explode('_', $txnRef)[0];

    $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?")->execute([$orderId]);
    $pdo->prepare("UPDATE payments SET status = 'success' WHERE vnp_txn_ref = ?")->execute([$txnRef]);

    unset($_SESSION['cart']);
    unset($_SESSION['order_id']);
    unset($_SESSION['order_total']);
}
?>

<div class="result-wrap">
    <?php if ($success): ?>
        <div class="result-icon result-success">✓</div>
        <div class="result-title">Payment Successful!</div>
        <p class="result-desc">Your order has been confirmed. Thank you for shopping with us!</p>
        <div style="font-size:13px;color:var(--gray-400);margin-bottom:32px;">
            Inventory has been updated automatically ✓
        </div>
        <a href="/shoe-store-main/" class="btn-primary">Continue Shopping →</a>
    <?php else: ?>
        <div class="result-icon result-fail">✕</div>
        <div class="result-title">Payment Failed!</div>
        <p class="result-desc">Something went wrong. Please try again.</p>
        <a href="/shoe-store-main/cart.php" class="btn btn-outline">Back to Cart</a>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>