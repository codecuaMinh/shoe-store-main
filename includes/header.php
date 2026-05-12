<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
</head>
<body>
<nav class="navbar">
    <a href="/shoe-store/" class="logo">👟 Shoe Store</a>
    <div class="nav-links">
    <a href="/shoe-store-main/">Home</a>
    <a href="/shoe-store/cart.php">
        Cart
        <span class="cart-count">
            <?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
        </span>
    </a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <span style="color:#555;font-size:14px;">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="/shoe-store/logout.php">Logout</a>
    <?php else: ?>
        <a href="/shoe-store/login.php">Login</a>
        <a href="/shoe-store/register.php">Register</a>
    <?php endif; ?>
    </div>
</nav>