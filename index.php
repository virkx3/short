
<?php require_once __DIR__ . '/header.php'; ?>
<div class="card">
  <h3>Shorten a long URL</h3>
  <form action="<?= h(base_url('actions/shorten.php')) ?>" method="post">
    <input type="url" name="long_url" placeholder="https://example.com/very/long/link" required style="width:100%;padding:10px;border-radius:10px;border:1px solid #2d3a79;background:#0b1230;color:#e9ecff">
    <button class="btn" type="submit">Shorten</button>
  </form>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
