<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Detail — StepStyle</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root { --accent: #f97316; --bg: #f9fafb; --white: #ffffff; --text2: #6b7280; --radius: 12px; }
  body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: #111827; }
  nav { background: var(--white); border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 60px; height: 68px; }
  .nav-logo { font-size: 22px; font-weight: 800; } .nav-logo span { color: var(--accent); }
  .nav-links { display: flex; gap: 28px; list-style: none; }
  .nav-links a { text-decoration: none; color: var(--text2); font-size: 15px; font-weight: 500; }
  .nav-links a:hover { color: var(--accent); }
  .breadcrumb { padding: 16px 60px; font-size: 13px; color: var(--text2); }
  .breadcrumb a { color: var(--accent); text-decoration: none; }
  .detail-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; padding: 30px 60px 60px; }
  .product-gallery { background: var(--white); border-radius: var(--radius); height: 420px; display: flex; align-items: center; justify-content: center; font-size: 120px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
  .product-gallery img { width: 100%; height: 100%; object-fit: contain; padding: 20px; }
  .product-details h1 { font-size: 30px; font-weight: 800; margin-bottom: 10px; }
  .cat-tag { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); font-weight: 600; margin-bottom: 12px; }
  .price { font-size: 36px; font-weight: 900; color: var(--accent); margin: 16px 0; }
  .stars { color: #facc15; font-size: 18px; margin-bottom: 16px; }
  .desc { color: var(--text2); font-size: 15px; line-height: 1.7; margin-bottom: 24px; border-top: 1px solid #e5e7eb; padding-top: 18px; }
  .stock-badge { display: inline-block; padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
  .in-stock { background: #dcfce7; color: #166534; }
  .out-stock { background: #fee2e2; color: #991b1b; }
  .order-box { background: var(--white); border-radius: var(--radius); padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
  .order-box h3 { margin-bottom: 16px; font-size: 17px; font-weight: 700; }
  .form-group { margin-bottom: 14px; }
  .form-group label { display: block; font-size: 13px; color: var(--text2); font-weight: 600; margin-bottom: 5px; }
  .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; outline: none; }
  .form-group input:focus { border-color: var(--accent); }
  .btn-order { width: 100%; padding: 14px; background: var(--accent); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 6px; }
  .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
  .related { padding: 0 60px 60px; }
  .related h2 { font-size: 24px; font-weight: 800; margin-bottom: 24px; }
  .related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
  .rel-card { background: var(--white); border-radius: var(--radius); overflow: hidden; text-decoration: none; color: inherit; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: 0.2s; }
  .rel-card:hover { transform: translateY(-4px); }
  .rel-img { height: 140px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 50px; overflow: hidden; }
  .rel-img img { width: 100%; height: 100%; object-fit: cover; }
  .rel-info { padding: 12px; }
  .rel-info h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
  .rel-info p { color: var(--accent); font-weight: 700; }
  footer { background: #111827; color: #9ca3af; text-align: center; padding: 24px; font-size: 13px; }
</style>
</head>
<body>
<?php
require_once '../db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id LIMIT 1"));

if (!$product) {
    echo "<div style='text-align:center;padding:80px;'><h2>Product not found.</h2><a href='products.php'>← Back to shop</a></div>";
    exit();
}

// Fetch reviews
$reviews = mysqli_query($conn, "SELECT * FROM reviews WHERE shoe_name LIKE '%{$product['product_name']}%' ORDER BY created_at DESC LIMIT 3");

// Handle order form
$order_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $cname   = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $qty     = (int)$_POST['quantity'];
    $pmethod = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $total   = $product['price'] * $qty;
    $today   = date('Y-m-d');

    mysqli_query($conn, "INSERT INTO orders (customer_name, product_name, quantity, price, order_date, payment_method, order_status)
        VALUES ('$cname', '{$product['product_name']}', $qty, $total, '$today', '$pmethod', 'pending')");

    $order_msg = '<div class="alert-success">✅ Order placed successfully! We will contact you on your number shortly.</div>';
}

// Related products
$related = mysqli_query($conn, "SELECT * FROM products WHERE category='{$product['category']}' AND id != $id AND status='available' LIMIT 4");
?>

<nav>
  <div class="nav-logo">Step<span>Style</span></div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Shop</a></li>
    <li><a href="index.php#contact">Contact</a></li>
  </ul>
</nav>

<div class="breadcrumb">
  <a href="index.php">Home</a> → <a href="products.php">Shop</a> → <?= htmlspecialchars($product['product_name']) ?>
</div>

<div class="detail-layout">
  <!-- Image -->
  <div class="product-gallery">
    <?php if ($product['product_image'] && file_exists('../admin/uploads/' . $product['product_image'])): ?>
      <img src="../admin/uploads/<?= $product['product_image'] ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
    <?php else: ?>
      👟
    <?php endif; ?>
  </div>

  <!-- Info -->
  <div class="product-details">
    <div class="cat-tag"><?= htmlspecialchars($product['category']) ?></div>
    <h1><?= htmlspecialchars($product['product_name']) ?></h1>
    <div class="stars">★★★★☆ <span style="color:#6b7280;font-size:14px;">(4.2 / 5)</span></div>
    <div class="price">Rs. <?= number_format($product['price'], 0) ?></div>

    <span class="stock-badge <?= $product['status']==='available'?'in-stock':'out-stock' ?>">
      <?= $product['status']==='available' ? "✅ In Stock ({$product['stock']} left)" : "❌ Out of Stock" ?>
    </span>

    <div class="desc"><?= nl2br(htmlspecialchars($product['description'] ?: 'Premium quality shoe from our latest collection.')) ?></div>

    <!-- Order Form -->
    <div class="order-box">
      <h3>📦 Place Your Order</h3>
      <?= $order_msg ?>
      <form method="POST">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" name="customer_name" placeholder="Ali Hassan" required>
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" name="phone" placeholder="0300-0000000" required>
        </div>
        <div class="form-group">
          <label>Quantity</label>
          <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" required>
        </div>
        <div class="form-group">
          <label>Payment Method</label>
          <select name="payment_method">
            <option value="cash">Cash on Delivery</option>
            <option value="online">JazzCash / EasyPaisa</option>
            <option value="card">Bank Transfer</option>
          </select>
        </div>
        <?php if ($product['status'] === 'available'): ?>
        <button type="submit" name="place_order" class="btn-order">🛒 Order Now</button>
        <?php else: ?>
        <button disabled class="btn-order" style="background:#9ca3af;cursor:not-allowed;">Out of Stock</button>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<!-- Reviews -->
<?php if (mysqli_num_rows($reviews) > 0): ?>
<div style="padding: 0 60px 40px;">
  <h2 style="font-size:22px;font-weight:800;margin-bottom:18px;">Customer Reviews</h2>
  <?php while ($r = mysqli_fetch_assoc($reviews)): ?>
  <div style="background:white;border-radius:10px;padding:18px 22px;margin-bottom:14px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
      <strong><?= htmlspecialchars($r['customer_name']) ?></strong>
      <span style="color:#facc15;"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></span>
    </div>
    <p style="color:#6b7280;font-size:14px;"><?= htmlspecialchars($r['review_text']) ?></p>
  </div>
  <?php endwhile; ?>
</div>
<?php endif; ?>

<!-- Related Products -->
<?php if (mysqli_num_rows($related) > 0): ?>
<div class="related">
  <h2>You Might Also Like</h2>
  <div class="related-grid">
    <?php while ($r = mysqli_fetch_assoc($related)): ?>
    <a href="product_detail.php?id=<?= $r['id'] ?>" class="rel-card">
      <div class="rel-img">
        <?php if ($r['product_image'] && file_exists('../admin/uploads/' . $r['product_image'])): ?>
          <img src="../admin/uploads/<?= $r['product_image'] ?>" alt="">
        <?php else: ?> 👟 <?php endif; ?>
      </div>
      <div class="rel-info">
        <h4><?= htmlspecialchars($r['product_name']) ?></h4>
        <p>Rs. <?= number_format($r['price'], 0) ?></p>
      </div>
    </a>
    <?php endwhile; ?>
  </div>
</div>
<?php endif; ?>

<footer>© 2026 StepStyle — Pakistan's Favourite Shoe Store</footer>
</body>
</html>
