<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_customer'])) {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $email         = mysqli_real_escape_string($conn, $_POST['email']);
    $phone         = mysqli_real_escape_string($conn, $_POST['phone']);
    $city          = mysqli_real_escape_string($conn, $_POST['city']);
    $address       = mysqli_real_escape_string($conn, $_POST['address']);
    $gender        = mysqli_real_escape_string($conn, $_POST['gender']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);

    $image_name = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['profile_image']['name']);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_dir . $image_name);
    }

    $sql = "INSERT INTO customers (customer_name, email, phone, city, address, gender, status, profile_image)
            VALUES ('$customer_name', '$email', '$phone', '$city', '$address', '$gender', '$status', '$image_name')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Customer record saved successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Add Customer</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:600px;">
  <form action="customer.php" method="POST" enctype="multipart/form-data">

    <?php
    $fields = [
      ['Customer Name','customer_name','text'],
      ['Email','email','email'],
      ['Phone','phone','text'],
      ['City','city','text'],
    ];
    foreach ($fields as [$label, $name, $type]):
    ?>
    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;"><?= $label ?></label>
      <input type="<?= $type ?>" name="<?= $name ?>" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <?php endforeach; ?>

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Address</label>
      <textarea name="address" rows="2" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;resize:vertical;"></textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
      <div>
        <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Gender</label>
        <select name="gender" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div>
        <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Status</label>
        <select name="status" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
    </div>

    <div style="margin-bottom:20px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Profile Image</label>
      <input type="file" name="profile_image" style="color:var(--text2);">
    </div>

    <button type="submit" name="save_customer" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Customer</button>
  </form>
</div>

</div></body></html>
