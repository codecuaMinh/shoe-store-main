<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /shoe-store-main/");
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
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
</head>
<body class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">SOLE<span style="color:var(--gold)">.</span>STORE</div>
        <div class="auth-sub">Create your account today</div>

        <?php if ($error): ?>
        <div class="auth-error">⚠ <?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="auth-success">
            ✓ <?= $success ?>
            <br><a href="/shoe-store-main/login.php" style="color:#0a5c36;font-weight:500;">Sign in now →</a>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-input" placeholder="Nguyen Van A" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Min. 6 characters" required>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;padding:12px;font-size:13px;margin-top:8px;">
                Create Account →
            </button>
        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="/shoe-store-main/login.php">Sign in</a>
        </div>
    </div>
</body>
</html>