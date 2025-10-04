
<?php
$cfg = include __DIR__ . '/../config.php';
if (!empty($cfg['DEBUG'])) { ini_set('display_errors', 1); error_reporting(E_ALL); }
$mysqli = @new mysqli($cfg['db']['host'], $cfg['db']['user'], $cfg['db']['pass'], $cfg['db']['name']);
if ($mysqli->connect_errno) { http_response_code(500); die('Database connection failed: ' . $mysqli->connect_error); }
$mysqli->set_charset('utf8mb4');
