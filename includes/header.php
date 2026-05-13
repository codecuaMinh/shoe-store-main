<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/shoe-store-main/assets/css/style.css?v=2">
</head>
<body>
<nav class="navbar">
    <a href="/shoe-store-main/" class="logo">SHOE<span class="logo-dot">.</span>STORE</a>
    <div class="nav-links">
        <a href="/shoe-store-main/">Home</a>
        <a href="#products">Products</a>
        <a href="/shoe-store-main/cart.php">
            Cart <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="nav-user"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="/shoe-store-main/logout.php">Logout</a>
        <?php else: ?>
            <a href="/shoe-store-main/login.php">Login</a>
            <a href="/shoe-store-main/register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>