<?php
require_once 'auth_check.php';
require_manager();
require_once '../db.php';

$message = '';

// ADD product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    $cat   = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $stat  = mysqli_real_escape_string($conn, $_POST['status']);

    $img_name = '';
    if (!empty($_FILES['product_image']['name'])) {
        $img_name = time() . '_' . basename($_FILES['product_image']['name']);
        $target = '../admin/uploads/' . $img_name;
        if (!is_dir('../admin/uploads/')) mkdir('../admin/uploads/', 0777, true);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target);
    }

    $sql = "INSERT INTO products (product_name, category, price, stock, description, product_image, status)
            VALUES ('$name','$cat','$price','$stock','$desc','$img_name','$stat')";
    $message = mysqli_query($conn, $sql)
        ? '<div class="alert-success">✅ Product added successfully!</div>'
        : '<div class="alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
}

// UPDATE stock/status only
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $id    = (int)$_POST['id'];
    $stock = (int)$_POST['stock'];
    $stat  = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE products SET stock='$stock', status='$stat' WHERE id=$id");
    $message = '<div class="alert-success">✅ Product updated!</div>';
}

include 'sidebar.php';

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
?>

<div class="page-header">
  <h1>Products</h1>
  <p>You can add products and update stock. Deletion requires Admin access.</p>
</div>

<?= $message ?>

<!-- Add Product Form -->
<div class="card">
  <h3 style="margin-bottom:18px;">➕ Add New Product</h3>
  <form method="POST" enctype="multipart/form-data" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="product_name" required>
    </div>
    <div class="form-group">
      <label>Category</label>
      <input type="text" name="category" required>
    </div>
    <div class="form-group">
      <label>Price (Rs.)</label>
      <input type="number" step="0.01" name="price" required>
    </div>
    <div class="form-group">
      <label>Stock Quantity</label>
      <input type="number" name="stock" required>
    </div>
    <div class="form-group" style="grid-column: span 2;">
      <label>Description</label>
      <textarea name="description" rows="2"></textarea>
    </div>
    <div class="form-group">
      <label>Product Image</label>
      <input type="file" name="product_image">
    </div>
    <div class="form-group">
      <label>Status</label>
      <select name="status">
        <option value="available">Available</option>
        <option value="out_of_stock">Out of Stock</option>
      </select>
    </div>
    <div style="grid-column: span 2;">
      <button type="submit" name="save_product" class="btn btn-primary">
        <i class="ti ti-plus"></i> Save Product
      </button>
    </div>
  </form>
</div>

<!-- Products Table -->
<div class="card">
  <h3 style="margin-bottom:16px;">📦 All Products</h3>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Image</th><th>Name</th><th>Category</th>
        <th>Price</th><th>Stock</th><th>Status</th><th>Update</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($p = mysqli_fetch_assoc($products)): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td>
        <?php if ($p['product_image']): ?>
          <img src="../admin/uploads/<?= $p['product_image'] ?>" style="width:45px;height:45px;object-fit:cover;border-radius:6px;">
        <?php else: ?>
          <div style="width:45px;height:45px;background:#1e2d45;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:20px;">👟</div>
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($p['product_name']) ?></td>
      <td><?= htmlspecialchars($p['category']) ?></td>
      <td>Rs. <?= number_format($p['price'], 0) ?></td>
      <td>
        <form method="POST" style="display:flex;gap:8px;align-items:center;">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <input type="number" name="stock" value="<?= $p['stock'] ?>" style="width:70px;padding:4px 8px;">
          <select name="status" style="padding:4px 6px;">
            <option value="available" <?= $p['status']==='available'?'selected':'' ?>>Available</option>
            <option value="out_of_stock" <?= $p['status']==='out_of_stock'?'selected':'' ?>>Out of Stock</option>
          </select>
          <button type="submit" name="update_product" class="btn btn-outline" style="padding:5px 10px;">Save</button>
        </form>
      </td>
      <td><span class="pill <?= $p['status']==='available'?'pill-green':'pill-red' ?>"><?= $p['status'] ?></span></td>
      <td>
        <span style="color:#f87171; font-size:12px;">🔒 Admin only</span>
      </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</div></body></html>
