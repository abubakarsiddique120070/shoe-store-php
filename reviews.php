<?php
require_once 'auth_check.php';
require_manager();
require_once '../db.php';
include 'sidebar.php';

$reviews = mysqli_query($conn, "SELECT * FROM reviews ORDER BY created_at DESC");
?>

<div class="page-header">
  <h1>Customer Reviews</h1>
  <p>Read-only view of all product reviews.</p>
</div>

<div class="card">
  <table>
    <thead>
      <tr><th>#</th><th>Customer</th><th>Shoe</th><th>Rating</th><th>Review</th><th>Date</th></tr>
    </thead>
    <tbody>
    <?php while ($r = mysqli_fetch_assoc($reviews)): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['customer_name']) ?></td>
      <td><?= htmlspecialchars($r['shoe_name']) ?></td>
      <td>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <span style="color:<?= $i <= $r['rating'] ? '#facc15' : '#3a4a6b' ?>;">★</span>
        <?php endfor; ?>
      </td>
      <td><?= htmlspecialchars($r['review_text']) ?></td>
      <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</div></body></html>
