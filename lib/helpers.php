
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$cfg = include __DIR__ . '/../config.php';

function base_url($path = '') {
    global $cfg;
    $base = rtrim($cfg['base_url'], '/');
    $path = ltrim($path, '/');
    return $path ? ($base . '/' . $path) : $base;
}
function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

function client_ip() {
    foreach (['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = $_SERVER[$k];
            if (strpos($ip, ',') !== false) $ip = trim(explode(',', $ip)[0]);
            return trim($ip);
        }
    }
    return '0.0.0.0';
}
function ua(){ return $_SERVER['HTTP_USER_AGENT'] ?? ''; }

function is_logged_in(){ return !empty($_SESSION['user']); }
function is_admin(){ return !empty($_SESSION['user']) && !empty($_SESSION['user']['is_admin']); }
function current_user_id(){ return $_SESSION['user']['id'] ?? null; }

function require_login(){
    if (!is_logged_in()){
        header('Location: ' . base_url('auth/login.php')); exit;
    }
}
function require_admin(){
    if (!is_admin()){
        header('Location: ' . base_url('admin/login.php')); exit;
    }
}

function record_visit($mysqli) {
    $uid = current_user_id();
    $ip  = client_ip();
    $ua  = ua();
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $ref  = $_SERVER['HTTP_REFERER'] ?? '';
    if ($stmt = $mysqli->prepare("INSERT INTO visits(user_id, ip, user_agent, path, referer) VALUES(?,?,?,?,?)")) {
        $stmt->bind_param('issss', $uid, $ip, $ua, $path, $ref);
        $stmt->execute();
    }
}

function record_log($mysqli, $action, $payloadArr = []) {
    $uid = current_user_id();
    $ip = client_ip();
    $payload = json_encode($payloadArr, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    if ($stmt = $mysqli->prepare("INSERT INTO logs(user_id, action, payload, ip) VALUES(?,?,?,?)")) {
        $stmt->bind_param('isss', $uid, $action, $payload, $ip);
        $stmt->execute();
    }
}

function random_code($len = 7) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $out = '';
    for ($i=0; $i<$len; $i++) {
        if (function_exists('random_int')) $out .= $alphabet[random_int(0, strlen($alphabet)-1)];
        else $out .= $alphabet[mt_rand(0, strlen($alphabet)-1)];
    }
    return $out;
}
function normalize_url($u) {
    $u = trim($u);
    if ($u === '') return $u;
    if (!preg_match('~^https?://~i', $u)) $u = 'https://' . $u;
    return $u;
}
function short_link($code) { return base_url('s/' . $code); }
