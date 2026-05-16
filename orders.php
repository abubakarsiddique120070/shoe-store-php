<?php
require_once 'auth_check.php';
require_manager();
require_once '../db.php';

$message = '';

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['order_status']);
    mysqli_query($conn, "UPDATE orders SET order_status='$status' WHERE id=$id");
    $message = '<div class="alert-success">✅ Order status updated!</div>';
}

include 'sidebar.php';

$filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$where  = $filter ? "WHERE order_status='$filter'" : '';
$orders = mysqli_query($conn, "SELECT * FROM orders $where ORDER BY created_at DESC");
?>

<div class="page-header">
  <h1>Orders</h1>
  <p>View and update order statuses.</p>
</div>

<?= $message ?>

<!-- Filter -->
<div style="display:flex;gap:10px;margin-bottom:20px;">
  <a href="orders.php" class="btn btn-outline <?= !$filter?'btn-primary':'' ?>">All</a>
  <a href="orders.php?status=pending"   class="btn btn-outline <?= $filter==='pending'?'btn-primary':'' ?>">Pending</a>
  <a href="orders.php?status=completed" class="btn btn-outline <?= $filter==='completed'?'btn-primary':'' ?>">Completed</a>
  <a href="orders.php?status=cancelled" class="btn btn-outline <?= $filter==='cancelled'?'btn-primary':'' ?>">Cancelled</a>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>#</th><th>Customer</th><th>Product</th><th>Qty</th>
        <th>Price</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($o = mysqli_fetch_assoc($orders)): ?>
    <tr>
      <td><?= $o['id'] ?></td>
      <td><?= htmlspecialchars($o['customer_name']) ?></td>
      <td><?= htmlspecialchars($o['product_name']) ?></td>
      <td><?= $o['quantity'] ?></td>
      <td>Rs. <?= number_format($o['price'], 0) ?></td>
      <td><?= $o['payment_method'] ?></td>
      <td>
        <?php
        $cls = match($o['order_status']) {
          'completed' => 'pill-green', 'cancelled' => 'pill-red', default => 'pill-amber'
        };
        ?>
        <span class="pill <?= $cls ?>"><?= $o['order_status'] ?></span>
      </td>
      <td><?= $o['order_date'] ?></td>
      <td>
        <form method="POST" style="display:flex;gap:6px;align-items:center;">
          <input type="hidden" name="id" value="<?= $o['id'] ?>">
          <select name="order_status" style="padding:4px 6px; font-size:12px;">
            <option value="pending"   <?= $o['order_status']==='pending'?'selected':'' ?>>Pending</option>
            <option value="completed" <?= $o['order_status']==='completed'?'selected':'' ?>>Completed</option>
            <option value="cancelled" <?= $o['order_status']==='cancelled'?'selected':'' ?>>Cancelled</option>
          </select>
          <button type="submit" name="update_status" class="btn btn-outline" style="padding:5px 10px;">Update</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</div></body></html>
