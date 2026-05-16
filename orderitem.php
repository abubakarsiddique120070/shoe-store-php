<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_orderitem'])) {
    $order_id     = (int)$_POST['order_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $quantity     = (int)$_POST['quantity'];
    $total_price  = (float)$_POST['total_price'];

    $sql = "INSERT INTO order_items (order_id, product_name, quantity, total_price)
            VALUES ('$order_id', '$product_name', '$quantity', '$total_price')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Order item saved successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Add Order Item</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:600px;">
  <form action="orderitem.php" method="POST">

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Select Order</label>
      <select name="order_id" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="">-- Select an Existing Order --</option>
        <?php
        $order_query = mysqli_query($conn, "SELECT id, customer_name FROM orders ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($order_query)) {
            echo "<option value='{$row['id']}'>Order #{$row['id']} — {$row['customer_name']}</option>";
        }
        ?>
      </select>
    </div>

    <?php
    $fields = [['Product Name','product_name','text'],['Quantity','quantity','number'],['Total Price (Rs.)','total_price','number']];
    foreach ($fields as [$label, $name, $type]):
    ?>
    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;"><?= $label ?></label>
      <input type="<?= $type ?>" name="<?= $name ?>" required <?= $type==='number'&&$name==='total_price'?'step="0.01"':'' ?> style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <?php endforeach; ?>

    <button type="submit" name="save_orderitem" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Order Item</button>
  </form>
</div>

</div></body></html>
