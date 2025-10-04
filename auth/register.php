
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
if (is_logged_in()) { header('Location: ' . base_url()); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if ($name==='') $name = null;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Invalid email.';
    if (strlen($pass) < 6) $error = 'Password must be 6+ chars.';
    if (!$error) {
        if ($stmt = $mysqli->prepare("SELECT id FROM users WHERE email=? LIMIT 1")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = 'Email already registered.';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmt2 = $mysqli->prepare("INSERT INTO users(name, email, password_hash) VALUES(?,?,?)");
                if (!$stmt2) die('DB error: ' . $mysqli->error);
                $stmt2->bind_param('sss', $name, $email, $hash);
                $stmt2->execute();
                $uid = $stmt2->insert_id;
                $_SESSION['user'] = ['id'=>$uid,'email'=>$email,'name'=>$name,'is_admin'=>0];
                header('Location: ' . base_url()); exit;
            }
            $stmt->close();
        } else { $error = 'DB error: ' . $mysqli->error; }
    }
}
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card" style="max-width:480px">
  <h3>Register</h3>
  <?php if ($error): ?><p class="muted" style="color:#ff9aa9"><?= h($error) ?></p><?php endif; ?>
  <form method="post">
    <input type="text" name="name" placeholder="Your name">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password (6+ chars)" required>
    <button class="btn" type="submit">Create Account</button>
  </form>
  <p class="muted">Already have an account? <a href="<?= h(base_url('auth/login.php')) ?>">Login</a></p>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
