<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_order'])) {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $product_name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    $quantity      = (int)$_POST['quantity'];
    $price         = (float)$_POST['price'];
    $order_date    = mysqli_real_escape_string($conn, $_POST['order_date']);
    $payment_meth  = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO orders (customer_name, product_name, quantity, price, order_date, payment_method, order_status)
            VALUES ('$customer_name', '$product_name', '$quantity', '$price', '$order_date', '$payment_meth', '$status')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Order recorded successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Add Order</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:600px;">
  <form action="order.php" method="POST" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    <?php
    $inputs = [
      ['Customer Name','customer_name','text'],
      ['Product Name','product_name','text'],
      ['Quantity','quantity','number'],
      ['Price (Rs.)','price','number'],
    ];
    foreach ($inputs as [$label, $name, $type]):
    ?>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;"><?= $label ?></label>
      <input type="<?= $type ?>" name="<?= $name ?>" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;" <?= $type==='number'&&$name==='price'?'step="0.01"':'' ?>>
    </div>
    <?php endforeach; ?>

    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Order Date</label>
      <input type="date" name="order_date" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Payment Method</label>
      <select name="payment_method" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="cash">Cash</option>
        <option value="card">Card</option>
        <option value="online">Online</option>
      </select>
    </div>
    <div style="grid-column:span 2;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Order Status</label>
      <select name="status" style="width:300px;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>
    <div style="grid-column:span 2;">
      <button type="submit" name="save_order" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Order</button>
    </div>
  </form>
</div>

</div></body></html>
