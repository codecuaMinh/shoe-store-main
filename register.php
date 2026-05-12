<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /shoe-store/");
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db.php';
    $full_name = htmlspecialchars($_POST['full_name'] ?? '');
    $email     = $_POST['email'] ?? '';
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Kiểm tra email đã tồn tại chưa
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered. Please use another email.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'customer')");
            $stmt->execute([$full_name, $email, $hashed]);
            $success = 'Account created successfully! You can now sign in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register – Shoe Store</title>
    <link rel="stylesheet" href="/shoe-store/assets/css/style.css">
</head>
<body style="background:#f5f5f5;display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <div style="background:#fff;border-radius:16px;padding:40px;width:380px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="font-size:40px;margin-bottom:8px;">👟</div>
            <h1 style="font-size:22px;font-weight:600;">Create Account</h1>
            <p style="color:#888;font-size:14px;margin-top:4px;">Join Shoe Store today</p>
        </div>

        <?php if ($error): ?>
        <div style="background:#fee;border:1px solid #fcc;color:#c00;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
            ⚠ <?= $error ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div style="background:#d1e7dd;border:1px solid #a3cfbb;color:#0a5c36;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
            ✓ <?= $success ?>
            <br><a href="/shoe-store/login.php" style="color:#0a5c36;font-weight:500;">Sign in now →</a>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Full Name</label>
                <input type="text" name="full_name" class="form-input" placeholder="Nguyen Van A" required>
            </div>
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Email</label>
                <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Min. 6 characters" required>
            </div>
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;padding:12px;font-size:15px;margin-top:8px;">
                Create Account →
            </button>
        </form>

        <p style="text-align:center;margin-top:20px;font-size:14px;color:#888;">
            Already have an account?
            <a href="/shoe-store/login.php" style="color:#222;font-weight:500;">Sign in</a>
        </p>
    </div>
</body>
</html>