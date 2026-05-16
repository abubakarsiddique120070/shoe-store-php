<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_review'])) {
    $customer = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $shoe     = mysqli_real_escape_string($conn, $_POST['shoe_name']);
    $rating   = (int)$_POST['rating'];
    $text     = mysqli_real_escape_string($conn, $_POST['review_text']);

    $sql = "INSERT INTO reviews (customer_name, shoe_name, rating, review_text)
            VALUES ('$customer', '$shoe', '$rating', '$text')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div style='color:green;padding:10px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;'>✅ Review submitted successfully!</div>";
    } else {
        $message = "<div style='color:red;padding:10px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

include("menu.php");
?>

<h2 style="margin-bottom:20px;">Customer Review Form</h2>
<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;max-width:500px;">
  <form action="review.php" method="POST">

    <?php foreach ([['Customer Name','customer_name'],['Shoe Name','shoe_name']] as [$label, $name]): ?>
    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;"><?= $label ?></label>
      <input type="text" name="<?= $name ?>" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
    </div>
    <?php endforeach; ?>

    <div style="margin-bottom:16px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Rating</label>
      <select name="rating" required style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
        <option value="5">5 Stars (Excellent)</option>
        <option value="4">4 Stars (Good)</option>
        <option value="3">3 Stars (Average)</option>
        <option value="2">2 Stars (Poor)</option>
        <option value="1">1 Star (Very Bad)</option>
      </select>
    </div>

    <div style="margin-bottom:20px;">
      <label style="display:block;color:var(--text2);font-size:13px;margin-bottom:6px;">Review Message</label>
      <textarea name="review_text" rows="4" placeholder="What did you think of the shoes?" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;resize:vertical;"></textarea>
    </div>

    <button type="submit" name="save_review" style="padding:10px 24px;background:var(--accent);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Submit Review</button>
  </form>
</div>

</div></body></html>
