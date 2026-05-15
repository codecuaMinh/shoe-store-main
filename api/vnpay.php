<?php
session_start();
require_once '../includes/db.php';

define('VNP_TMNCODE', 'GRARNE8L');
define('VNP_HASHSECRET', 'DD51961KXQRFBBM7JHMDX3FZ9XXWH4U0');
define('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
define('VNP_RETURNURL', 'http://localhost:8080/shoe-store-main/payment_return.php');

$order_id    = $_SESSION['order_id'] ?? 0;
$order_total = $_SESSION['order_total'] ?? 0;

if (!$order_id) {
    header("Location: /shoe-store-main/");
    exit;
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
$vnp_TxnRef     = $order_id . '_' . time();
$vnp_Amount     = $order_total * 100;
$vnp_CreateDate = date('YmdHis');

$inputData = [
    'vnp_Version'    => '2.1.0',
    'vnp_TmnCode'    => VNP_TMNCODE,
    'vnp_Amount'     => $vnp_Amount,
    'vnp_Command'    => 'pay',
    'vnp_CreateDate' => $vnp_CreateDate,
    'vnp_CurrCode'   => 'VND',
    'vnp_IpAddr' => '127.0.0.1',
    'vnp_Locale'     => 'vn',
    'vnp_OrderInfo'  => 'Thanh toan don hang ' . $order_id,
    'vnp_OrderType'  => 'billpayment',
    'vnp_ReturnUrl'  => VNP_RETURNURL,
    'vnp_TxnRef'     => $vnp_TxnRef,
];

ksort($inputData);
$query  = http_build_query($inputData);
$hmac   = hash_hmac('sha512', $query, VNP_HASHSECRET);
$vnpUrl = VNP_URL . '?' . $query . '&vnp_SecureHash=' . $hmac;

$stmt = $pdo->prepare("INSERT INTO payments (order_id, vnp_txn_ref, amount, status) VALUES (?, ?, ?, 'pending')");
$stmt->execute([$order_id, $vnp_TxnRef, $order_total]);

header('Location: ' . $vnpUrl);
exit;