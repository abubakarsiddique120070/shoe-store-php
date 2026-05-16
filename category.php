<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_category'])) {
    $category_name   = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description     = mysqli_real_escape_string($conn, $_POST['description']);
    $parent_category = (int)$_POST['parent_category'];
    $status          = mysqli_real_escape_string($conn, $_POST['status']);
    $seo_keyword     = mysqli_real_escape_string($conn, $_POST['seo_keyword']);

    $image_name = '';
    if (!empty($_FILES['category_image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['category_image']['name']);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        move_uploaded_file($_FILES['category_image']['tmp_name'], $target_dir . $image_name);
    }

    $sql = "INSERT INTO categories (category_name, description, parent_category, status, category_image, seo_keyword)
            VALUES ('$category_name', '$description', '$parent_category', '$status', '$image_name', '$seo_keyword')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Category saved successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Product Categories</h2>

<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:600px;">
  <form action="category.php" method="POST" enctype="multipart/form-data">

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Category Name</label>
      <input type="text" name="category_name" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Description</label>
      <textarea name="description" rows="3" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;resize:vertical;"></textarea>
    </div>

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Parent Category</label>
      <select name="parent_category" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="0">Main Category</option>
        <?php
        $cats = mysqli_query($conn, "SELECT id, category_name FROM categories ORDER BY category_name");
        while ($row = mysqli_fetch_assoc($cats)) {
            echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
        }
        ?>
      </select>
    </div>

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Status</label>
      <select name="status" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Category Image</label>
      <input type="file" name="category_image" style="color:var(--text2);">
    </div>

    <div style="margin-bottom:20px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">SEO Keyword</label>
      <input type="text" name="seo_keyword" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>

    <button type="submit" name="save_category" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Category</button>
  </form>
</div>

</div></body></html>
