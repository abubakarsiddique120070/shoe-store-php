<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_complaint'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $msg_text  = mysqli_real_escape_string($conn, $_POST['message'] ?? '');

    $sql = "INSERT INTO complains (full_name, phone, email, message)
            VALUES ('$full_name', '$phone', '$email', '$msg_text')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Complaint registered successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Customer Complaint Form</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:500px;">
  <form action="complains.php" method="POST">

    <?php foreach ([['Full Name','full_name','text'],['Phone Number','phone','text'],['Email Address','email','email']] as [$label, $name, $type]): ?>
    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;"><?= $label ?></label>
      <input type="<?= $type ?>" name="<?= $name ?>" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <?php endforeach; ?>

    <div style="margin-bottom:20px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Message</label>
      <textarea name="message" rows="3" placeholder="Describe the issue..." style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;resize:vertical;"></textarea>
    </div>

    <button type="submit" name="save_complaint" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Complaint</button>
  </form>
</div>

</div></body></html>
