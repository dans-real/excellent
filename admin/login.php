<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
if (isLoggedIn()) { header('Location: ' . BASE_URL . '/admin/'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  if ($username && $password) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password'])) {
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['admin'] = $admin;
      $pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$admin['id']]);
      header('Location: ' . BASE_URL . '/admin/');
      exit;
    } else { $error = 'Username atau password salah.'; }
  } else { $error = 'Harap isi semua kolom.'; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — UKM-F EXCELLENT</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
<style>
body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:linear-gradient(135deg,#1E3A5F 0%,#132540 100%)}
.login-box{background:#fff;border-radius:16px;padding:2.5rem;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.login-logo{text-align:center;margin-bottom:2rem}
.login-logo-mark{width:56px;height:56px;background:#1E3A5F;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#F5A623;margin:0 auto .75rem;font-family:Georgia,serif}
.login-logo h1{font-size:18px;font-weight:700;color:#1e293b}
.login-logo p{font-size:13px;color:#94a3b8;margin-top:.25rem}
.login-box .fg{margin-bottom:1.1rem}
.login-box .fl{font-size:13px}
.login-btn{width:100%;padding:.75rem;font-size:15px;justify-content:center;border-radius:10px;margin-top:.25rem}
.login-footer{text-align:center;margin-top:1.5rem;font-size:12px;color:#94a3b8}
.login-footer a{color:#1E3A5F;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<div class="login-box">
  <div class="login-logo">
    <div class="login-logo-mark">EX</div>
    <h1>Admin Panel</h1>
    <p>UKM-F EXCELLENT</p>
  </div>
  <?php if($error): ?><div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="fg"><label class="fl">Username</label><input type="text" name="username" class="fi" placeholder="admin" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"></div>
    <div class="fg"><label class="fl">Password</label><input type="password" name="password" class="fi" placeholder="••••••••" required></div>
    <button type="submit" class="btn btn-primary login-btn">Masuk →</button>
  </form>
  <div class="login-footer"><a href="<?= BASE_URL ?>">← Kembali ke Website</a></div>
</div>
</body>
</html>
