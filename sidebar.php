<?php
require_once 'auth_check.php';
require_manager();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manager Panel — Shoe Store</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --bg: #07090f; --bg2: #0c101a; --surface: #141c2e;
    --accent: #10b981; --text: #e8eeff; --text2: #7a8cbb;
    --border: rgba(16,185,129,0.15); --sidebar-w: 240px; --radius: 14px;
  }
  body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
  .sidebar { width: var(--sidebar-w); background: var(--bg2); border-right: 1px solid var(--border); position: fixed; top: 0; bottom: 0; display: flex; flex-direction: column; }
  .brand { padding: 22px 20px; border-bottom: 1px solid var(--border); }
  .brand h2 { color: var(--accent); font-size: 16px; }
  .brand p  { color: var(--text2); font-size: 11px; margin-top: 3px; }
  .nav { flex: 1; padding: 10px 0; }
  .nav a { display: flex; align-items: center; gap: 10px; padding: 11px 20px; color: var(--text2); text-decoration: none; font-size: 14px; transition: 0.2s; }
  .nav a:hover, .nav a.active { background: var(--surface); color: var(--text); border-left: 3px solid var(--accent); }
  .nav a i { font-size: 18px; }
  .nav .section-label { padding: 14px 20px 4px; font-size: 10px; letter-spacing: 1px; color: #3a4a6b; text-transform: uppercase; }
  .sidebar-footer { padding: 16px 20px; border-top: 1px solid var(--border); }
  .sidebar-footer a { color: #f87171; text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .user-badge { background: var(--surface); border-radius: 8px; padding: 10px 12px; margin-bottom: 10px; font-size: 12px; }
  .user-badge span { color: var(--accent); font-weight: bold; }
  .main { margin-left: var(--sidebar-w); flex: 1; padding: 30px; }
  .page-header { margin-bottom: 28px; }
  .page-header h1 { font-size: 24px; }
  .page-header p  { color: var(--text2); font-size: 14px; margin-top: 4px; }
  .card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 24px; margin-bottom: 24px; }
  .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 24px; }
  .stat-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 20px; }
  .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text2); }
  .stat-value { font-size: 30px; font-weight: bold; margin: 8px 0; }
  table { width: 100%; border-collapse: collapse; }
  th { padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text2); border-bottom: 1px solid var(--border); }
  td { padding: 13px 12px; border-bottom: 1px solid rgba(99,120,210,0.08); font-size: 14px; }
  tr:hover td { background: rgba(16,185,129,0.04); }
  .pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
  .pill-green { background: rgba(16,185,129,0.15); color: #6ee7b7; }
  .pill-amber { background: rgba(251,191,36,0.15); color: #fde68a; }
  .pill-red   { background: rgba(248,113,113,0.15); color: #f87171; }
  .pill-blue  { background: rgba(93,126,245,0.15); color: #a5b4fc; }
  .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: 0.2s; }
  .btn-primary { background: var(--accent); color: #fff; }
  .btn-primary:hover { background: #059669; }
  .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text2); }
  .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
  .form-group { margin-bottom: 18px; }
  .form-group label { display: block; margin-bottom: 6px; font-size: 13px; color: var(--text2); }
  .form-group input, .form-group select, .form-group textarea {
    width: 100%; padding: 10px 12px; background: var(--bg2);
    border: 1px solid rgba(99,120,210,0.2); border-radius: 8px;
    color: var(--text); font-size: 14px; outline: none;
  }
  .form-group input:focus, .form-group select:focus { border-color: var(--accent); }
  .alert-success { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
  .alert-error   { background: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3); color: #f87171; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
  .no-access { text-align:center; padding: 60px 20px; color: var(--text2); }
  .no-access i { font-size: 48px; color: #f87171; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="brand">
    <h2>🗂️ Manager Panel</h2>
    <p>Shoe Store Management</p>
  </div>
  <nav class="nav">
    <div class="section-label">Overview</div>
    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
      <i class="ti ti-layout-dashboard"></i> Dashboard
    </a>
    <div class="section-label">Manage</div>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">
      <i class="ti ti-shoe"></i> Products
    </a>
    <a href="orders.php" class="<?= basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : '' ?>">
      <i class="ti ti-shopping-cart"></i> Orders
    </a>
    <a href="complaints.php" class="<?= basename($_SERVER['PHP_SELF']) === 'complaints.php' ? 'active' : '' ?>">
      <i class="ti ti-message-circle-exclamation"></i> Complaints
    </a>
    <a href="reviews.php" class="<?= basename($_SERVER['PHP_SELF']) === 'reviews.php' ? 'active' : '' ?>">
      <i class="ti ti-star"></i> Reviews
    </a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-badge">
      Logged in as:<br><span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
    </div>
    <a href="logout.php"><i class="ti ti-logout"></i> Logout</a>
  </div>
</aside>

<div class="main">
