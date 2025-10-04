
<?php
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/conn.php';
record_visit($mysqli);
$site = $cfg['site_name'] ?? 'Short';
?>
<!doctype html>
<html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($site) ?> - URL Shortener</title>
<link rel="stylesheet" href="<?= h(base_url('public/assets/style.css')) ?>">
</head><body><div class="container">
<div class="hero">
  <div>
    <div class="pill">⚡ Fast URL shortener · Random short codes</div>
    <h1><?= h($site) ?></h1>
    <p class="muted">Paste long URL → short like <span class="kbd">/s/abc1234</span></p>
    <div class="nav">
      <a href="<?= h(base_url()) ?>">Home</a>
      <?php if (is_admin()): ?>
        <a href="<?= h(base_url('admin')) ?>">Admin</a>
      <?php endif; ?>
      <?php if (!is_logged_in()): ?>
        <a href="<?= h(base_url('auth/login.php')) ?>">Login</a>
        <a href="<?= h(base_url('auth/register.php')) ?>">Register</a>
      <?php else: ?>
        <span class="kbd">Hi, <?= h($_SESSION['user']['name'] ?: $_SESSION['user']['email']) ?></span>
        <a href="<?= h(base_url('auth/logout.php')) ?>">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</div>
