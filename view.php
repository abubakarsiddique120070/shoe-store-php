<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

include("menu.php");

$result = mysqli_query($conn, "SELECT product_name, SUM(view_count) as total_views FROM views GROUP BY product_name ORDER BY total_views DESC");
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
$max_views = !empty($data) ? $data[0]['total_views'] : 1;
?>

<h2 style="margin-bottom:6px;">📊 Product Popularity (Views)</h2>
<p style="color:var(--text2);margin-bottom:24px;">Which shoes your customers are looking at the most.</p>

<div style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);padding:24px;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr>
        <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text2);border-bottom:1px solid var(--border);">Product Name</th>
        <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text2);border-bottom:1px solid var(--border);">Total Views</th>
        <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text2);border-bottom:1px solid var(--border);">Popularity</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $row):
        $percent = ($max_views > 0) ? ($row['total_views'] / $max_views) * 100 : 0;
      ?>
      <tr>
        <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><strong><?= htmlspecialchars($row['product_name']) ?></strong></td>
        <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);font-size:14px;"><?= number_format($row['total_views']) ?> views</td>
        <td style="padding:14px 12px;border-bottom:1px solid rgba(99,120,210,0.08);width:40%;">
          <div style="width:100%;background:var(--bg);height:10px;border-radius:5px;">
            <div style="width:<?= $percent ?>%;height:100%;background:linear-gradient(90deg,#5d7ef5,#9b59f5);border-radius:5px;"></div>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($data)): ?>
      <tr><td colspan="3" style="padding:30px;text-align:center;color:var(--text2);">No view data yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</div></body></html>
