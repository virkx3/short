
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . base_url()); exit; }

$long = normalize_url($_POST['long_url'] ?? '');
if (!filter_var($long, FILTER_VALIDATE_URL)) { die('Invalid URL.'); }

$code = null;
for ($i=0; $i<8; $i++) {
    $attempt = random_code(7);
    if ($stmt = $mysqli->prepare("SELECT id FROM urls WHERE code=? LIMIT 1")) {
        $stmt->bind_param('s', $attempt);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) { $code = $attempt; $stmt->close(); break; }
        $stmt->close();
    } else { die('DB error: ' . $mysqli->error); }
}
if (!$code) die('Could not generate a unique code. Try again.');

$uid = current_user_id();
$ip = client_ip();
$stmt = $mysqli->prepare("INSERT INTO urls(code, long_url, user_id, creator_ip) VALUES(?,?,?,?)");
if (!$stmt) die('DB error: ' . $mysqli->error);
$stmt->bind_param('ssis', $code, $long, $uid, $ip);
$stmt->execute();
record_log($mysqli, 'shorten_url', ['code'=>$code,'long'=>$long]);
$short = short_link($code);
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card">
  <h3>Short link created!</h3>
  <div class="muted">
    <p><strong>Short:</strong> <a href="<?= h($short) ?>" target="_blank"><?= h($short) ?></a></p>
    <p><strong>Long:</strong> <?= h($long) ?></p>
  </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
