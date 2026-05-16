<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_product'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    $cat   = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $stat  = mysqli_real_escape_string($conn, $_POST['status']);

    $img_name = '';
    if (!empty($_FILES['product_image']['name'])) {
        $img_name  = time() . '_' . basename($_FILES['product_image']['name']);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_dir . $img_name)) {
            $img_name = '';
        }
    }

    $sql = "INSERT INTO products (product_name, category, price, stock, description, product_image, status)
            VALUES ('$name', '$cat', '$price', '$stock', '$desc', '$img_name', '$stat')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Product saved successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Add Product</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:600px;">
  <form action="product.php" method="POST" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Product Name</label>
      <input type="text" name="product_name" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Category</label>
      <input type="text" name="category" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Price (Rs.)</label>
      <input type="number" step="0.01" name="price" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Stock Quantity</label>
      <input type="number" name="stock" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <div style="grid-column:span 2;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Description</label>
      <textarea name="description" rows="3" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;resize:vertical;"></textarea>
    </div>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Product Image</label>
      <input type="file" name="product_image" style="color:var(--text2);">
    </div>
    <div>
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Status</label>
      <select name="status" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="available">Available</option>
        <option value="out_of_stock">Out of Stock</option>
      </select>
    </div>
    <div style="grid-column:span 2;">
      <button type="submit" name="save_product" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Product</button>
    </div>
  </form>
</div>

</div></body></html>
