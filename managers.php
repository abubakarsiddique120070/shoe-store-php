<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

include 'menu.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_manager'])) {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name','$email','$password','manager')";
    $message = mysqli_query($conn, $sql)
        ? '<div style="color:green;padding:12px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;">✅ Manager added successfully!</div>'
        : '<div style="color:red;padding:12px;background:rgba(255,0,0,0.1);border-radius:8px;margin-bottom:16px;">❌ Error: ' . mysqli_error($conn) . '</div>';
}

if (isset($_GET['delete_id'])) {
    $did = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$did AND role='manager'");
    $message = '<div style="color:green;padding:12px;background:rgba(0,255,0,0.1);border-radius:8px;margin-bottom:16px;">✅ Manager removed.</div>';
}

$managers = mysqli_query($conn, "SELECT * FROM users WHERE role='manager' ORDER BY created_at DESC");
?>

<h1 style="margin-bottom:6px">Manage Managers</h1>
<p style="color:var(--text2);margin-bottom:24px">Add or remove manager accounts. Managers have limited access.</p>

<?= $message ?>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;margin-bottom:24px;">
  <h3 style="margin-bottom:18px">➕ Add New Manager</h3>
  <form method="POST">
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:14px;align-items:end;">
      <?php foreach ([['Full Name','name','text'],['Email','email','email'],['Password','password','password']] as [$label,$name,$type]): ?>
      <div>
        <label style="display:block;font-size:12px;color:var(--text2);margin-bottom:5px;"><?= $label ?></label>
        <input type="<?= $type ?>" name="<?= $name ?>" required placeholder="<?= $label ?>" style="width:100%;padding:10px 12px;background:var(--bg2);border:1px solid rgba(99,120,210,0.2);border-radius:8px;color:var(--text);font-size:14px;outline:none;">
      </div>
      <?php endforeach; ?>
      <button type="submit" name="add_manager" style="padding:10px 20px;background:var(--accent);color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;white-space:nowrap;">Add Manager</button>
    </div>
  </form>
</div>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;">
  <h3 style="margin-bottom:18px">👥 All Managers</h3>
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr>
        <?php foreach (['#','Name','Email','Role','Created','Action'] as $h): ?>
        <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text2);border-bottom:1px solid var(--border);"><?= $h ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php while ($m = mysqli_fetch_assoc($managers)): ?>
    <tr>
      <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><?= $m['id'] ?></td>
      <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><?= htmlspecialchars($m['name']) ?></td>
      <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><?= htmlspecialchars($m['email']) ?></td>
      <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><span style="background:rgba(16,185,129,0.15);color:#6ee7b7;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;">Manager</span></td>
      <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><?= date('d M Y', strtotime($m['created_at'])) ?></td>
      <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);"><a href="managers.php?delete_id=<?= $m['id'] ?>" style="background:rgba(248,113,113,0.15);color:#f87171;border:none;padding:5px 12px;border-radius:6px;font-size:12px;cursor:pointer;text-decoration:none;" onclick="return confirm('Remove this manager?')">🗑 Remove</a></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</div></body></html>
