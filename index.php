<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Ticker -->
<div class="ticker">
    <div class="ticker-track">
        <span class="ticker-item">Free Shipping Over 500K</span>
        <span class="ticker-item">New Arrivals Every Week</span>
        <span class="ticker-item">Authentic Products Only</span>
        <span class="ticker-item">Secure VNPay Payment</span>
        <span class="ticker-item">30-Day Easy Returns</span>
        <span class="ticker-item">Free Shipping Over 500K</span>
        <span class="ticker-item">New Arrivals Every Week</span>
        <span class="ticker-item">Authentic Products Only</span>
        <span class="ticker-item">Secure VNPay Payment</span>
        <span class="ticker-item">30-Day Easy Returns</span>
    </div>
</div>

<!-- Hero -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-eyebrow-line"></div>
            <span>New Collection 2025</span>
        </div>
        <h1>STEP INTO <em>STYLE</em></h1>
        <p class="hero-desc">Premium sneakers from the world's top brands. Discover your next pair today.</p>
        <div class="hero-actions">
            <a href="#products" class="btn-primary">Shop Now →</a>
            <a href="#featured" class="btn-ghost">View Featured</a>
        </div>
        <div class="hero-stats">
            <div>
                <div class="hero-stat-num"><?= count($products) ?>+</div>
                <div class="hero-stat-label">Products</div>
            </div>
            <div>
                <div class="hero-stat-num">6</div>
                <div class="hero-stat-label">Top Brands</div>
            </div>
            <div>
                <div class="hero-stat-num">100%</div>
                <div class="hero-stat-label">Authentic</div>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="hero-circle"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-circle hero-circle-3"></div>
        <div class="hero-shoe-emoji">👟</div>
    </div>
</section>

<!-- Promo -->
<div class="promo-grid">
    <div class="promo-card promo-1" onclick="location.href='#products'">
        <div class="promo-emoji">🏷️</div>
        <div class="promo-tag">Limited Time</div>
        <h3>UP TO 30% OFF</h3>
        <p>Selected sneakers this week only</p>
        <a class="promo-btn">Grab Deal →</a>
    </div>
    <div class="promo-card promo-2" onclick="location.href='#products'">
        <div class="promo-emoji">🚚</div>
        <div class="promo-tag">Free Shipping</div>
        <h3>ORDERS 500K+</h3>
        <p>Nationwide delivery</p>
        <a class="promo-btn">Shop Now →</a>
    </div>
    <div class="promo-card promo-3" onclick="location.href='#products'">
        <div class="promo-emoji">✨</div>
        <div class="promo-tag">New Arrivals</div>
        <h3>FRESH DROPS</h3>
        <p>New styles every week</p>
        <a class="promo-btn">Explore →</a>
    </div>
</div>

<!-- Categories -->
<div class="category-strip" id="featured">
    <div class="category-item active" onclick="filterProducts('all', this)">
        <div class="cat-icon">👞</div>
        <div class="cat-name">All</div>
    </div>
    <div class="category-item" onclick="filterProducts('Sneaker', this)">
        <div class="cat-icon">👟</div>
        <div class="cat-name">Sneaker</div>
    </div>
    <div class="category-item" onclick="filterProducts('Skate', this)">
        <div class="cat-icon">🛹</div>
        <div class="cat-name">Skate</div>
    </div>
    <div class="category-item" onclick="filterProducts('Running', this)">
        <div class="cat-icon">🏃</div>
        <div class="cat-name">Running</div>
    </div>
    <div class="category-item" onclick="filterProducts('Lifestyle', this)">
        <div class="cat-icon">🌟</div>
        <div class="cat-name">Lifestyle</div>
    </div>
</div>

<!-- Products -->
<section id="products" style="padding:2px 0;">
    <div style="background:var(--white);padding:32px 48px 20px;">
        <div class="filter-row">
            <div>
                <div class="section-label">
                    <div class="section-label-line"></div>
                    <span>Our Collection</span>
                </div>
                <div class="section-title">ALL PRODUCTS</div>
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterTab('all', this)">All</button>
                <button class="filter-tab" onclick="filterTab('Sneaker', this)">Sneaker</button>
                <button class="filter-tab" onclick="filterTab('Skate', this)">Skate</button>
                <button class="filter-tab" onclick="filterTab('Lifestyle', this)">Lifestyle</button>
                <button class="filter-tab" onclick="filterTab('Running', this)">Running</button>
            </div>
        </div>
    </div>
    <div class="product-grid" id="product-grid">
        <?php foreach ($products as $i => $p): ?>
        <div class="product-card" data-category="<?= htmlspecialchars($p['category']) ?>"
             onclick="location.href='product.php?id=<?= $p['id'] ?>'">
            <?php if ($i < 2): ?>
                <div class="product-badge badge-new">NEW</div>
            <?php elseif ($p['stock'] < 10): ?>
                <div class="product-badge badge-low">LOW STOCK</div>
            <?php endif; ?>
            <div class="product-img-wrap">
                <img class="product-img"
                     src="<?= htmlspecialchars($p['image_url']) ?>"
                     alt="<?= htmlspecialchars($p['name']) ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500'">
                <div class="product-cta">VIEW PRODUCT →</div>
            </div>
            <div class="product-info">
                <div class="product-category"><?= htmlspecialchars($p['category']) ?></div>
                <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                <div class="product-footer">
                    <div class="product-price"><?= number_format($p['price'], 0, ',', '.') ?>đ</div>
                    <div class="product-stock <?= $p['stock'] < 10 ? 'low-stock' : '' ?>">
                        <?= $p['stock'] < 10 ? '🔥 '.$p['stock'].' LEFT' : '✓ IN STOCK' ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Brands -->
<div class="brand-strip">
    <div class="brand-grid">
        <div class="brand-item">Nike</div>
        <div class="brand-item">Adidas</div>
        <div class="brand-item">Converse</div>
        <div class="brand-item">Vans</div>
        <div class="brand-item">Puma</div>
        <div class="brand-item">New Balance</div>
    </div>
</div>

<!-- Why Us -->
<section class="why-section">
    <div class="container">
        <div style="text-align:center;margin-bottom:48px;">
            <div class="section-label" style="justify-content:center;">
                <div class="section-label-line"></div>
                <span style="color:var(--gold)">Why Choose Us</span>
                <div class="section-label-line"></div>
            </div>
            <div class="section-title" style="color:var(--white)">WE'RE DIFFERENT</div>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">🔒</div>
                <h4>SECURE PAYMENT</h4>
                <p>Powered by VNPay with HMAC-SHA512 encryption for every transaction.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">🚚</div>
                <h4>FAST DELIVERY</h4>
                <p>Nationwide shipping within 2–3 business days guaranteed.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">↩️</div>
                <h4>EASY RETURNS</h4>
                <p>30-day hassle-free return policy. No questions asked.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">⭐</div>
                <h4>100% AUTHENTIC</h4>
                <p>Every pair sourced directly from authorized brand distributors.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>