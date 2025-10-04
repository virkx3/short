
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
require_admin();
$q = $mysqli->query("SELECT id, code, long_url, user_id, click_count, created_at, last_clicked_at FROM urls ORDER BY id DESC LIMIT 300");
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card"><h3>All URLs</h3>
  <div class="muted" style="overflow:auto">
    <table class="table">
      <tr><th>#</th><th>Code</th><th>Short</th><th>Long URL</th><th>User</th><th>Clicks</th><th>Created</th><th>Last Click</th></tr>
      <?php if ($q): while($r=$q->fetch_assoc()): $short = short_link($r['code']); ?>
        <tr><td><?= (int)$r['id'] ?></td><td><?= h($r['code']) ?></td><td><a href="<?= h($short) ?>" target="_blank"><?= h($short) ?></a></td><td style="max-width:420px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= h($r['long_url']) ?></td><td><?= h($r['user_id']) ?></td><td><?= (int)$r['click_count'] ?></td><td><?= h($r['created_at']) ?></td><td><?= h($r['last_clicked_at']) ?></td></tr>
      <?php endwhile; endif; ?>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
