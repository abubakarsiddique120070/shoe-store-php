<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $note   = mysqli_real_escape_string($conn, $_POST['note']);

    $sql = "INSERT INTO payments (method, note) VALUES ('$method', '$note')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Payment method saved successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Payment Method</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:500px;">
  <form action="paymentmethod.php" method="POST">

    <div style="margin-bottom:20px;">
      <p style="color:var(--text2);font-size:13px;margin-bottom:14px;font-weight:600;">Select Payment Method:</p>
      <?php
      $methods = ['Cash on Delivery','Bank Transfer','JazzCash / EasyPaisa','Credit Card'];
      foreach ($methods as $m):
      ?>
      <label style="display:flex;align-items:center;gap:10px;color:var(--text);font-size:14px;margin-bottom:12px;cursor:pointer;">
        <input type="radio" name="payment_method" value="<?= $m ?>" required style="accent-color:var(--accent);">
        <?= $m ?>
      </label>
      <?php endforeach; ?>
    </div>

    <div style="margin-bottom:20px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Additional Note</label>
      <textarea name="note" rows="3" placeholder="Enter any extra details here..." style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;resize:vertical;"></textarea>
    </div>

    <button type="submit" name="confirm_payment" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Confirm Payment</button>
  </form>
</div>

</div></body></html>
