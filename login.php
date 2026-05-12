<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /shoe-store/");
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

        // Nếu là admin thì vào admin dashboard
        if ($user['role'] === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            header("Location: /shoe-store/admin/");
        } else {
            header("Location: /shoe-store/");
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
    <link rel="stylesheet" href="/shoe-store/assets/css/style.css">
</head>
<body style="background:#f5f5f5;display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <div style="background:#fff;border-radius:16px;padding:40px;width:380px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="font-size:40px;margin-bottom:8px;">👟</div>
            <h1 style="font-size:22px;font-weight:600;">Welcome back</h1>
            <p style="color:#888;font-size:14px;margin-top:4px;">Sign in to your account</p>
        </div>

        <?php if ($error): ?>
        <div style="background:#fee;border:1px solid #fcc;color:#c00;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
            ⚠ <?= $error ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Email</label>
                <input type="email" name="email" class="form-input" placeholder="your@email.com" required autofocus>
            </div>
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;padding:12px;font-size:15px;margin-top:8px;">
                Sign In →
            </button>
        </form>

        <p style="text-align:center;margin-top:20px;font-size:14px;color:#888;">
            Don't have an account? 
            <a href="/shoe-store/register.php" style="color:#222;font-weight:500;">Register</a>
        </p>
    </div>
</body>
</html>