<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: /shoe-store-main/admin/");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/db.php';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        header("Location: /shoe-store-main/admin/");
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login – Shoe Store</title>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
    <link rel="stylesheet" href="/shoe-store-main/assets/css/admin.css">
</head>
<body class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">👟 SOLE<span style="color:var(--gold)">.</span>STORE</div>
        <div class="auth-sub">Admin Panel — Sign in to continue</div>
        <?php if ($error): ?>
        <div class="auth-error">⚠ <?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="admin@shoestore.com" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;padding:12px;font-size:13px;margin-top:8px;">
                Login →
            </button>
        </form>
        <div class="auth-footer">
            <a href="/shoe-store-main/">← Back to Shop</a>
        </div>
    </div>
</body>
</html>