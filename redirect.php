
<?php
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/conn.php';

$code = $_GET['c'] ?? '';
$code = preg_replace('~[^A-Za-z0-9_-]~', '', $code);
if ($code === '') { http_response_code(404); echo 'Not found'; exit; }

if ($stmt = $mysqli->prepare("SELECT id, long_url FROM urls WHERE code=? LIMIT 1")) {
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows !== 1) { http_response_code(404); echo 'Link not found'; exit; }
    $stmt->bind_result($url_id, $long);
    $stmt->fetch();
    $stmt->close();
} else { http_response_code(500); die('DB error: ' . $mysqli->error); }

$ip = client_ip();
$user_agent = ua();
$ref = $_SERVER['HTTP_REFERER'] ?? '';
if ($stmt = $mysqli->prepare("INSERT INTO clicks(url_id, ip, user_agent, referer) VALUES(?,?,?,?)")) {
    $stmt->bind_param('isss', $url_id, $ip, $user_agent, $ref);
    $stmt->execute();
}
$mysqli->query("UPDATE urls SET click_count = click_count + 1, last_clicked_at = NOW() WHERE id = " . (int)$url_id);
header('Location: ' . $long, true, 302); exit;
