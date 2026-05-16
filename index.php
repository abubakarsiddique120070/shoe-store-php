<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StepStyle — Premium Shoe Store</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --primary: #111827; --accent: #f97316; --bg: #f9fafb;
    --text: #111827; --text2: #6b7280; --white: #ffffff;
    --radius: 12px;
  }
  body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); }

  /* NAVBAR */
  nav {
    background: var(--white); border-bottom: 1px solid #e5e7eb;
    position: sticky; top: 0; z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 60px; height: 68px;
  }
  .nav-logo { font-size: 22px; font-weight: 800; color: var(--text); }
  .nav-logo span { color: var(--accent); }
  .nav-links { display: flex; gap: 30px; list-style: none; }
  .nav-links a { text-decoration: none; color: var(--text2); font-size: 15px; font-weight: 500; transition: color 0.2s; }
  .nav-links a:hover { color: var(--accent); }
  .nav-actions { display: flex; gap: 14px; align-items: center; }
  .btn-nav { padding: 9px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
  .btn-outline-nav { border: 2px solid var(--accent); color: var(--accent); background: transparent; }
  .btn-fill-nav { background: var(--accent); color: white; }

  /* HERO */
  .hero {
    background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #374151 100%);
    color: white; padding: 90px 60px;
    display: flex; align-items: center; justify-content: space-between; gap: 40px;
    min-height: 520px;
  }
  .hero-text { max-width: 560px; }
  .hero-badge { display: inline-block; background: rgba(249,115,22,0.2); color: #fb923c; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
  .hero-text h1 { font-size: 52px; font-weight: 800; line-height: 1.15; margin-bottom: 20px; }
  .hero-text h1 span { color: var(--accent); }
  .hero-text p { color: #9ca3af; font-size: 17px; line-height: 1.7; margin-bottom: 32px; }
  .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
  .btn-hero-primary { padding: 14px 32px; background: var(--accent); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; text-decoration: none; }
  .btn-hero-secondary { padding: 14px 32px; background: transparent; color: white; border: 2px solid rgba(255,255,255,0.3); border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; }
  .hero-visual { font-size: 180px; line-height: 1; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.5)); }

  /* TRUST BAR */
  .trust-bar { background: var(--accent); color: white; display: flex; justify-content: center; gap: 60px; padding: 18px 60px; flex-wrap: wrap; }
  .trust-item { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; }

  /* CATEGORIES */
  .section { padding: 70px 60px; }
  .section-header { text-align: center; margin-bottom: 40px; }
  .section-header h2 { font-size: 34px; font-weight: 800; margin-bottom: 10px; }
  .section-header p { color: var(--text2); font-size: 16px; }
  .cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
  .cat-card {
    background: var(--white); border-radius: var(--radius); overflow: hidden;
    text-align: center; padding: 30px 20px; cursor: pointer;
    border: 2px solid transparent; transition: all 0.2s; text-decoration: none; color: var(--text);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .cat-card:hover { border-color: var(--accent); transform: translateY(-4px); box-shadow: 0 8px 24px rgba(249,115,22,0.15); }
  .cat-icon { font-size: 40px; margin-bottom: 12px; }
  .cat-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
  .cat-card p { color: var(--text2); font-size: 13px; }

  /* PRODUCTS */
  .products-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
  .product-card {
    background: var(--white); border-radius: var(--radius);
    overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: all 0.2s; cursor: pointer; text-decoration: none; color: var(--text);
  }
  .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
  .product-img {
    height: 200px; background: #f3f4f6;
    display: flex; align-items: center; justify-content: center;
    font-size: 70px; position: relative; overflow: hidden;
  }
  .product-img img { width: 100%; height: 100%; object-fit: cover; }
  .badge-new { position: absolute; top: 10px; left: 10px; background: var(--accent); color: white; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; }
  .product-info { padding: 16px; }
  .product-info .cat-tag { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); font-weight: 600; margin-bottom: 6px; }
  .product-info h3 { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
  .product-stars { color: #facc15; font-size: 13px; margin-bottom: 10px; }
  .product-footer { display: flex; align-items: center; justify-content: space-between; }
  .product-price { font-size: 18px; font-weight: 800; color: var(--accent); }
  .btn-cart { background: var(--accent); color: white; border: none; border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s; }
  .btn-cart:hover { background: #ea6c0a; }

  /* FEATURES */
  .features { background: var(--primary); color: white; }
  .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
  .feature-card { text-align: center; padding: 30px 20px; }
  .feature-icon { font-size: 42px; margin-bottom: 16px; }
  .feature-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
  .feature-card p { color: #9ca3af; font-size: 14px; line-height: 1.6; }

  /* CONTACT */
  .contact-form { background: var(--white); border-radius: var(--radius); padding: 40px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: var(--text2); }
  .form-group input, .form-group textarea {
    width: 100%; padding: 11px 14px;
    border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 14px; outline: none; transition: border 0.2s;
  }
  .form-group input:focus, .form-group textarea:focus { border-color: var(--accent); }
  .btn-submit { width: 100%; background: var(--accent); color: white; border: none; padding: 14px; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; }
  .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }

  /* FOOTER */
  footer { background: var(--primary); color: #9ca3af; }
  .footer-top { padding: 60px; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; }
  .footer-brand h3 { color: white; font-size: 20px; margin-bottom: 12px; }
  .footer-brand p { font-size: 14px; line-height: 1.7; }
  .footer-col h4 { color: white; font-size: 14px; font-weight: 700; margin-bottom: 14px; }
  .footer-col a { display: block; color: #9ca3af; text-decoration: none; font-size: 14px; margin-bottom: 8px; }
  .footer-col a:hover { color: var(--accent); }
  .footer-bottom { border-top: 1px solid #374151; padding: 20px 60px; display: flex; justify-content: space-between; font-size: 13px; }
</style>
</head>
<body>

<?php
require_once '../db.php';

// Handle complaint submission
$complaint_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_complaint'])) {
    $name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    mysqli_query($conn, "INSERT INTO complains (full_name, phone, email, message) VALUES ('$name','$phone','$email','$message')");
    $complaint_msg = '<div class="alert-success">✅ Your message has been sent! We\'ll contact you soon.</div>';
}

// Fetch products and categories
$products   = mysqli_query($conn, "SELECT * FROM products WHERE status='available' ORDER BY created_at DESC LIMIT 8");
$categories = mysqli_query($conn, "SELECT * FROM categories WHERE status='active' LIMIT 4");
?>

<!-- NAVBAR -->
<nav>
  <div class="nav-logo">Step<span>Style</span></div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Shop</a></li>
    <li><a href="index.php#categories">Categories</a></li>
    <li><a href="index.php#contact">Contact</a></li>
  </ul>
  <div class="nav-actions">
    <a href="../index.php" class="btn-nav btn-outline-nav">Staff Login</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-text">
    <div class="hero-badge">🔥 New Collection 2026</div>
    <h1>Walk in <span>Style</span>,<br>Step with Confidence</h1>
    <p>Discover Pakistan's finest collection of premium shoes. From sports to formal — find your perfect pair at unbeatable prices.</p>
    <div class="hero-btns">
      <a href="products.php" class="btn-hero-primary">Shop Now</a>
      <a href="index.php#categories" class="btn-hero-secondary">Browse Categories</a>
    </div>
  </div>
  <div class="hero-visual">👟</div>
</section>

<!-- TRUST BAR -->
<div class="trust-bar">
  <div class="trust-item">🚚 Free Delivery over Rs. 3000</div>
  <div class="trust-item">↩️ 7-Day Easy Returns</div>
  <div class="trust-item">✅ 100% Authentic Products</div>
  <div class="trust-item">💳 Cash on Delivery Available</div>
</div>

<!-- CATEGORIES -->
<section class="section" id="categories" style="background: white;">
  <div class="section-header">
    <h2>Shop by Category</h2>
    <p>Find exactly what you're looking for</p>
  </div>
  <div class="cat-grid">
    <?php
    $cat_icons = ['👞','👠','👟','🥿'];
    $i = 0;
    mysqli_data_seek($categories, 0);
    while ($cat = mysqli_fetch_assoc($categories)):
    ?>
    <a href="products.php?category=<?= urlencode($cat['category_name']) ?>" class="cat-card">
      <div class="cat-icon"><?= $cat_icons[$i++ % 4] ?></div>
      <h3><?= htmlspecialchars($cat['category_name']) ?></h3>
      <p><?= htmlspecialchars($cat['seo_keyword']) ?></p>
    </a>
    <?php endwhile; ?>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section">
  <div class="section-header">
    <h2>Featured Products</h2>
    <p>Handpicked bestsellers just for you</p>
  </div>
  <div class="products-grid">
    <?php $idx = 0; while ($p = mysqli_fetch_assoc($products)): ?>
    <a href="product_detail.php?id=<?= $p['id'] ?>" class="product-card">
      <div class="product-img">
        <?php if ($p['product_image'] && file_exists('../admin/uploads/' . $p['product_image'])): ?>
          <img src="../admin/uploads/<?= $p['product_image'] ?>" alt="<?= htmlspecialchars($p['product_name']) ?>">
        <?php else: ?>
          👟
        <?php endif; ?>
        <?php if ($idx < 2): ?><div class="badge-new">NEW</div><?php endif; ?>
      </div>
      <div class="product-info">
        <div class="cat-tag"><?= htmlspecialchars($p['category']) ?></div>
        <h3><?= htmlspecialchars($p['product_name']) ?></h3>
        <div class="product-stars">★★★★★</div>
        <div class="product-footer">
          <div class="product-price">Rs. <?= number_format($p['price'], 0) ?></div>
          <button class="btn-cart" onclick="event.preventDefault(); alert('Order via WhatsApp or call us!')">Add to Cart</button>
        </div>
      </div>
    </a>
    <?php $idx++; endwhile; ?>
  </div>
  <div style="text-align:center; margin-top:36px;">
    <a href="products.php" class="btn-hero-primary" style="display:inline-block; text-decoration:none;">View All Products →</a>
  </div>
</section>

<!-- FEATURES -->
<section class="section features">
  <div class="section-header">
    <h2>Why Choose StepStyle?</h2>
    <p style="color:#9ca3af;">We go the extra mile for your comfort</p>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">🚚</div>
      <h3>Fast Delivery</h3>
      <p>Get your shoes delivered within 2-3 working days across Pakistan. Free delivery on orders above Rs. 3000.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">✅</div>
      <h3>100% Authentic</h3>
      <p>Every pair is sourced directly from authorized distributors. Quality guaranteed or full refund.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">💬</div>
      <h3>24/7 Support</h3>
      <p>Our customer team is available around the clock via WhatsApp, phone, or email to assist you.</p>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="section" id="contact">
  <div class="section-header">
    <h2>Get in Touch</h2>
    <p>Send us a message and we'll get back to you quickly</p>
  </div>
  <div class="contact-form">
    <?= $complaint_msg ?>
    <form method="POST">
      <div class="form-row">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="full_name" placeholder="Ali Hassan" required>
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" name="phone" placeholder="0300-1234567" required>
        </div>
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@example.com" required>
      </div>
      <div class="form-group">
        <label>Message</label>
        <textarea name="message" rows="4" placeholder="How can we help you?"></textarea>
      </div>
      <button type="submit" name="send_complaint" class="btn-submit">Send Message 📨</button>
    </form>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <h3>👟 StepStyle</h3>
      <p>Pakistan's trusted online shoe store. Premium footwear at honest prices delivered to your doorstep.</p>
    </div>
    <div class="footer-col">
      <h4>Quick Links</h4>
      <a href="index.php">Home</a>
      <a href="products.php">All Products</a>
      <a href="index.php#categories">Categories</a>
      <a href="index.php#contact">Contact Us</a>
    </div>
    <div class="footer-col">
      <h4>Categories</h4>
      <a href="products.php?category=Men+Shoes">Men Shoes</a>
      <a href="products.php?category=Women+Shoes">Women Shoes</a>
      <a href="products.php?category=Sports">Sports</a>
      <a href="products.php?category=Casual">Casual</a>
    </div>
    <div class="footer-col">
      <h4>Contact</h4>
      <a href="#">📞 0300-1234567</a>
      <a href="#">✉️ info@stepstyle.pk</a>
      <a href="#">📍 Faisalabad, Pakistan</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2026 StepStyle. All rights reserved.</span>
    <span>Made with ❤️ in Pakistan</span>
  </div>
</footer>

</body>
</html>
