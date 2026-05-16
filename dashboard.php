<?php
require_once 'auth_check.php';
require_manager();
require_once '../db.php';
include 'sidebar.php';

$totalProducts  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM products"))[0];
$totalOrders    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$openComplaints = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM complains WHERE status='open'"))[0];

$recentOrders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 6");
?>

<div class="page-header">
  <h1>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h1>
  <p>Here's what's happening in the store today.</p>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-label">Total Products</div>
    <div class="stat-value"><?= $totalProducts ?></div>
    <div style="color:#10b981; font-size:13px;">In inventory</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Total Orders</div>
    <div class="stat-value"><?= $totalOrders ?></div>
    <div style="color:#10b981; font-size:13px;">All time</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Open Complaints</div>
    <div class="stat-value" style="color:#f87171;"><?= $openComplaints ?></div>
    <div style="color:#f87171; font-size:13px;">Need attention</div>
  </div>
</div>

<div class="card">
  <h3 style="margin-bottom:16px;">Recent Orders</h3>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($recentOrders)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td>Rs. <?= number_format($row['price'], 0) ?></td>
        <td>
          <?php
          $cls = match($row['order_status']) {
            'completed' => 'pill-green', 'cancelled' => 'pill-red', default => 'pill-amber'
          };
          ?>
          <span class="pill <?= $cls ?>"><?= $row['order_status'] ?></span>
        </td>
        <td><?= $row['order_date'] ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</div></body></html>
