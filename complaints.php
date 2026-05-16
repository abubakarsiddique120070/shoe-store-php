<?php
require_once 'auth_check.php';
require_manager();
require_once '../db.php';

$message = '';

// Mark as resolved
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {
    $id = (int)$_POST['resolve_id'];
    mysqli_query($conn, "UPDATE complains SET status='resolved' WHERE id=$id");
    $message = '<div class="alert-success">✅ Complaint marked as resolved!</div>';
}

include 'sidebar.php';

$complaints = mysqli_query($conn, "SELECT * FROM complains ORDER BY created_at DESC");
?>

<div class="page-header">
  <h1>Complaints</h1>
  <p>Manage and resolve customer complaints.</p>
</div>

<?= $message ?>

<div class="card">
  <table>
    <thead>
      <tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Message</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php while ($c = mysqli_fetch_assoc($complaints)): ?>
    <tr>
      <td><?= $c['id'] ?></td>
      <td><?= htmlspecialchars($c['full_name']) ?></td>
      <td><?= $c['phone'] ?></td>
      <td><?= $c['email'] ?></td>
      <td><?= htmlspecialchars($c['message'] ?? '—') ?></td>
      <td>
        <span class="pill <?= $c['status']==='resolved'?'pill-green':'pill-amber' ?>">
          <?= $c['status'] ?>
        </span>
      </td>
      <td>
        <?php if ($c['status'] === 'open'): ?>
        <form method="POST">
          <input type="hidden" name="resolve_id" value="<?= $c['id'] ?>">
          <button type="submit" class="btn btn-primary" style="padding:5px 12px; font-size:12px;">Resolve</button>
        </form>
        <?php else: ?>
          <span style="color:#10b981; font-size:12px;">✔ Done</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</div></body></html>
