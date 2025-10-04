
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
require_admin();

$stats = ['urls'=>0,'clicks'=>0,'users'=>0,'visits'=>0,'unique_ips'=>0,'conversions'=>0];
if ($r = $mysqli->query("SELECT COUNT(*) c FROM urls")) $stats['urls'] = (int)$r->fetch_assoc()['c'];
if ($r = $mysqli->query("SELECT SUM(click_count) c FROM urls")) $stats['clicks'] = (int)($r->fetch_assoc()['c'] ?? 0);
if ($r = $mysqli->query("SELECT COUNT(*) c FROM users")) $stats['users'] = (int)$r->fetch_assoc()['c'];
if ($r = $mysqli->query("SELECT COUNT(*) c FROM visits")) $stats['visits'] = (int)$r->fetch_assoc()['c'];
if ($r = $mysqli->query("SELECT COUNT(DISTINCT ip) c FROM visits")) $stats['unique_ips'] = (int)$r->fetch_assoc()['c'];
if ($r = $mysqli->query("SELECT COUNT(*) c FROM logs WHERE action='shorten_url'")) $stats['conversions'] = (int)$r->fetch_assoc()['c'];

$recent = $mysqli->query("SELECT id, code, long_url, click_count, created_at FROM urls ORDER BY id DESC LIMIT 20");
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card">
  <h3>Dashboard</h3>
  <p class="muted">Totals — URLs: <?= (int)$stats['urls'] ?>, Clicks: <?= (int)$stats['clicks'] ?>, Users: <?= (int)$stats['users'] ?>, Visits: <?= (int)$stats['visits'] ?>, Unique IPs: <?= (int)$stats['unique_ips'] ?>, Conversions: <?= (int)$stats['conversions'] ?></p>
  <div class="muted">
    <table class="table">
      <tr><th>#</th><th>Code</th><th>Short</th><th>Clicks</th><th>Created</th></tr>
      <?php if ($recent): while($row=$recent->fetch_assoc()): $short = short_link($row['code']); ?>
        <tr><td><?= (int)$row['id'] ?></td><td><?= h($row['code']) ?></td><td><a href="<?= h($short) ?>" target="_blank"><?= h($short) ?></a></td><td><?= (int)$row['click_count'] ?></td><td><?= h($row['created_at']) ?></td></tr>
      <?php endwhile; endif; ?>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
