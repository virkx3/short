
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
require_admin();
$summary = ['today'=>0,'week'=>0,'month'=>0];
$mysqli->query("SET time_zone = '+05:30'");
if ($r = $mysqli->query("SELECT COUNT(*) c FROM visits WHERE DATE(created_at)=CURDATE()")) $summary['today'] = (int)$r->fetch_assoc()['c'];
if ($r = $mysqli->query("SELECT COUNT(*) c FROM visits WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")) $summary['week'] = (int)$r->fetch_assoc()['c'];
if ($r = $mysqli->query("SELECT COUNT(*) c FROM visits WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")) $summary['month'] = (int)$r->fetch_assoc()['c'];
$vis = $mysqli->query("SELECT id, user_id, ip, path, referer, created_at FROM visits ORDER BY id DESC LIMIT 200");
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card">
  <h3>Visit Summary</h3>
  <p class="muted">Today: <?= (int)$summary['today'] ?> · 7 days: <?= (int)$summary['week'] ?> · 30 days: <?= (int)$summary['month'] ?></p>
  <div class="muted" style="overflow:auto">
    <table class="table">
      <tr><th>#</th><th>User</th><th>IP</th><th>Path</th><th>Referer</th><th>Time</th></tr>
      <?php if ($vis): while($r=$vis->fetch_assoc()): ?>
        <tr><td><?= (int)$r['id'] ?></td><td><?= h($r['user_id']) ?></td><td><?= h($r['ip']) ?></td><td><?= h($r['path']) ?></td><td><?= h($r['referer']) ?></td><td><?= h($r['created_at']) ?></td></tr>
      <?php endwhile; endif; ?>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
