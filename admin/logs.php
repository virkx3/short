
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
require_admin();
$logs = $mysqli->query("SELECT id, user_id, action, payload, ip, created_at FROM logs ORDER BY id DESC LIMIT 200");
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card"><h3>Logs</h3>
  <div class="muted" style="overflow:auto">
    <table class="table">
      <tr><th>#</th><th>User</th><th>Action</th><th>Payload</th><th>IP</th><th>Time</th></tr>
      <?php if ($logs): while($r=$logs->fetch_assoc()): ?>
        <tr><td><?= (int)$r['id'] ?></td><td><?= h($r['user_id']) ?></td><td><?= h($r['action']) ?></td><td><code><?= h($r['payload']) ?></code></td><td><?= h($r['ip']) ?></td><td><?= h($r['created_at']) ?></td></tr>
      <?php endwhile; endif; ?>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
