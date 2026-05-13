<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /shoe-store-main/");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db.php';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            header("Location: /shoe-store-main/admin/");
        } else {
            header("Location: /shoe-store-main/");
        }
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
    <title>Login – Shoe Store</title>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
</head>
<body class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">SOLE<span style="color:var(--gold)">.</span>STORE</div>
        <div class="auth-sub">Welcome back — Sign in to your account</div>

        <?php if ($error): ?>
        <div class="auth-error">⚠ <?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="your@email.com" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;padding:12px;font-size:13px;margin-top:8px;">
                Sign In →
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account?
            <a href="/shoe-store-main/register.php">Register</a>
        </div>
    </div>
</body>
</html>