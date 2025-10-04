
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
require_admin();
$q = $mysqli->query("SELECT id, name, email, is_admin, created_at, last_login_at FROM users ORDER BY id DESC LIMIT 200");
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card"><h3>Users</h3>
  <div class="muted" style="overflow:auto">
    <table class="table">
      <tr><th>#</th><th>Name</th><th>Email</th><th>Admin</th><th>Created</th><th>Last Login</th></tr>
      <?php if ($q): while($r=$q->fetch_assoc()): ?>
        <tr><td><?= (int)$r['id'] ?></td><td><?= h($r['name']) ?></td><td><?= h($r['email']) ?></td><td><?= (int)$r['is_admin'] ? 'Yes' : 'No' ?></td><td><?= h($r['created_at']) ?></td><td><?= h($r['last_login_at']) ?></td></tr>
      <?php endwhile; endif; ?>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
