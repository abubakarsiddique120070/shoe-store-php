<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=admin_system;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("<div style='color:red;padding:20px;'>DB Error: " . $e->getMessage() . "</div>");
}

$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalOrders    = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalProducts  = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$openComplaints = $pdo->query("SELECT COUNT(*) FROM complains WHERE status='open'")->fetchColumn();

$recentOrders = $pdo->query("
    SELECT id, price AS total_amount, order_status AS status, created_at, customer_name
    FROM orders ORDER BY created_at DESC LIMIT 5
")->fetchAll();

function initials(string $name): string {
    $parts = explode(' ', trim($name));
    return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
function pillClass(string $status): string {
    return match(strtolower($status)) {
        'completed' => 'pill-green',
        'pending'   => 'pill-amber',
        'cancelled' => 'pill-red',
        default     => 'pill-blue',
    };
}
function avatarColor(string $name): string {
    $colors = [
        ['bg' => '#dbeafe', 'fg' => '#1e40af'],
        ['bg' => '#dcfce7', 'fg' => '#166534'],
        ['bg' => '#fef9c3', 'fg' => '#854d0e']
    ];
    $c = $colors[abs(crc32($name)) % count($colors)];
    return "background:{$c['bg']};color:{$c['fg']}";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard — Shoe Store</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  :root{--bg:#07090f;--bg2:#0c101a;--surface:#141c2e;--accent:#5d7ef5;--text:#e8eeff;--text2:#7a8cbb;--border:rgba(99,120,210,0.15);--sidebar-w:240px;--radius:14px}
  body{font-family:'Segoe UI',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}
  .sidebar{width:var(--sidebar-w);height:100vh;background:var(--bg2);border-right:1px solid var(--border);position:fixed;top:0;display:flex;flex-direction:column}
  .brand{padding:20px;border-bottom:1px solid var(--border);text-align:center;font-weight:bold;color:var(--accent);font-size:15px}
  .nav-link{display:flex;align-items:center;gap:9px;padding:12px 20px;color:var(--text2);text-decoration:none;transition:.3s;font-size:14px}
  .nav-link:hover,.nav-link.active{background:var(--surface);color:var(--text);border-left:3px solid var(--accent)}
  .nav-link i{font-size:18px}
  .sidebar-footer{margin-top:auto;padding:16px 20px;border-top:1px solid var(--border)}
  .sidebar-footer a{color:#f87171;text-decoration:none;font-size:13px;display:flex;align-items:center;gap:8px}
  .user-badge{background:var(--surface);border-radius:8px;padding:10px 12px;margin-bottom:10px;font-size:12px;color:var(--text2)}
  .user-badge span{color:var(--accent);font-weight:bold}
  .main{margin-left:var(--sidebar-w);flex:1;padding:30px}
  .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:30px}
  .stat-card{background:var(--surface);padding:20px;border-radius:var(--radius);border:1px solid var(--border)}
  .stat-label{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text2)}
  .stat-value{font-size:28px;font-weight:bold;margin:10px 0}
  .panel{background:var(--surface);border-radius:var(--radius);padding:20px;margin-top:20px}
  .order-row{display:flex;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)}
  .order-info{flex:1;margin-left:10px}
  .pill{padding:4px 10px;border-radius:20px;font-size:11px;font-weight:bold;text-transform:uppercase}
  .pill-green{background:rgba(52,211,153,.2);color:#6ee7b7}
  .pill-amber{background:rgba(251,191,36,.2);color:#fde68a}
  .pill-red{background:rgba(248,113,113,.2);color:#f87171}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">👟 SHOE STORE ADMIN</div>
  <nav>
    <a href="menu.php"          class="nav-link <?= basename($_SERVER['PHP_SELF'])==='menu.php'?'active':'' ?>"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
    <a href="customer.php"      class="nav-link <?= basename($_SERVER['PHP_SELF'])==='customer.php'?'active':'' ?>"><i class="ti ti-users"></i> Customers</a>
    <a href="product.php"       class="nav-link <?= basename($_SERVER['PHP_SELF'])==='product.php'?'active':'' ?>"><i class="ti ti-shoe"></i> Products</a>
    <a href="order.php"         class="nav-link <?= basename($_SERVER['PHP_SELF'])==='order.php'?'active':'' ?>"><i class="ti ti-shopping-cart"></i> Orders</a>
    <a href="category.php"      class="nav-link <?= basename($_SERVER['PHP_SELF'])==='category.php'?'active':'' ?>"><i class="ti ti-category"></i> Categories</a>
    <a href="orderitem.php"     class="nav-link <?= basename($_SERVER['PHP_SELF'])==='orderitem.php'?'active':'' ?>"><i class="ti ti-list-details"></i> Order Items</a>
    <a href="paymentmethod.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])==='paymentmethod.php'?'active':'' ?>"><i class="ti ti-credit-card"></i> Payments</a>
    <a href="review.php"        class="nav-link <?= basename($_SERVER['PHP_SELF'])==='review.php'?'active':'' ?>"><i class="ti ti-star"></i> Reviews</a>
    <a href="complains.php"     class="nav-link <?= basename($_SERVER['PHP_SELF'])==='complains.php'?'active':'' ?>"><i class="ti ti-message-circle-exclamation"></i> Complaints</a>
    <a href="view.php"          class="nav-link <?= basename($_SERVER['PHP_SELF'])==='view.php'?'active':'' ?>"><i class="ti ti-chart-bar"></i> Analytics</a>
    <a href="managers.php"      class="nav-link <?= basename($_SERVER['PHP_SELF'])==='managers.php'?'active':'' ?>"><i class="ti ti-shield"></i> Managers</a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-badge">Logged in as:<br><span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span></div>
    <a href="logout.php"><i class="ti ti-logout"></i> Logout</a>
  </div>
</aside>
<div class="main">
  <h1>Welcome 👋</h1>
  <p style="color:var(--text2);margin-bottom:24px;">Here is the status of your Shoe E-commerce Store.</p>
  <div class="stats-grid">
    <div class="stat-card"><div class="stat-label">TOTAL CUSTOMERS</div><div class="stat-value"><?= $totalCustomers ?></div></div>
    <div class="stat-card"><div class="stat-label">TOTAL ORDERS</div><div class="stat-value"><?= $totalOrders ?></div></div>
    <div class="stat-card"><div class="stat-label">PRODUCTS</div><div class="stat-value"><?= $totalProducts ?></div></div>
    <div class="stat-card"><div class="stat-label">OPEN COMPLAINTS</div><div class="stat-value" style="color:#f87171"><?= $openComplaints ?></div></div>
  </div>
  <div class="panel">
    <h3 style="margin-bottom:16px">Recent Orders</h3>
    <?php if (empty($recentOrders)): ?>
      <p style="color:var(--text2);padding:20px">No orders yet.</p>
    <?php else: ?>
      <?php foreach ($recentOrders as $order): ?>
      <div class="order-row">
        <div style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;<?= avatarColor($order['customer_name']) ?>"><?= initials($order['customer_name']) ?></div>
        <div class="order-info"><b><?= htmlspecialchars($order['customer_name']) ?></b><br><small style="color:var(--text2)"><?= $order['created_at'] ?></small></div>
        <div style="margin-right:20px">Rs. <?= number_format($order['total_amount'], 0) ?></div>
        <div class="pill <?= pillClass($order['status']) ?>"><?= $order['status'] ?></div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
