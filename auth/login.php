
<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/conn.php';
if (is_logged_in()) { header('Location: ' . base_url()); exit; }

$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Invalid email.';
    if (!$error) {
        if ($stmt = $mysqli->prepare("SELECT id, email, name, password_hash, is_admin FROM users WHERE email=? LIMIT 1")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $em, $name, $hash, $is_admin);
                $stmt->fetch();
                if (password_verify($pass, $hash)) {
                    $_SESSION['user'] = ['id'=>$id,'email'=>$em,'name'=>$name,'is_admin'=>(int)$is_admin];
                    $mysqli->query("UPDATE users SET last_login_at=NOW() WHERE id=" . (int)$id);
                    header('Location: ' . base_url()); exit;
                }
            }
            $error = 'Wrong email or password.';
            $stmt->close();
        } else { $error = 'DB error: ' . $mysqli->error; }
    }
}
?>
<?php require_once __DIR__ . '/../header.php'; ?>
<div class="card" style="max-width:420px">
  <h3>Login</h3>
  <?php if ($error): ?><p class="muted" style="color:#ff9aa9"><?= h($error) ?></p><?php endif; ?>
  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="btn" type="submit">Login</button>
  </form>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
