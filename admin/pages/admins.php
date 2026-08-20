<?php
$pageTitle = 'Kelola Admin';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
$pdo = getDB();
$me = currentAdmin();

// Only superadmin can access
if ($me['role'] !== 'superadmin') {
  setFlash('error', '❌ Akses ditolak. Hanya superadmin.');
  header('Location: ' . BASE_URL . '/admin/'); exit;
}

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = $_POST;
  if ($d['_action'] === 'add') {
    if ($pdo->prepare("SELECT id FROM admins WHERE username=?")->execute([$d['username']]) && $pdo->query("SELECT COUNT(*) FROM admins WHERE username='" . $pdo->quote($d['username']) . "'")->fetchColumn()) {
      setFlash('error', '❌ Username sudah digunakan.');
    } elseif (strlen($d['password']) < 6) {
      setFlash('error', '❌ Password minimal 6 karakter.');
    } else {
      $hash = password_hash($d['password'], PASSWORD_BCRYPT);
      $pdo->prepare("INSERT INTO admins (name,username,password,email,role) VALUES (?,?,?,?,?)")->execute([$d['name'],$d['username'],$hash,$d['email'],$d['role']]);
      setFlash('success', '✅ Admin berhasil ditambahkan!');
    }
  } elseif ($d['_action'] === 'edit' && $d['_id']) {
    $update = "UPDATE admins SET name=?,email=?,role=?";
    $params = [$d['name'],$d['email'],$d['role']];
    if (!empty($d['password'])) {
      if (strlen($d['password']) < 6) { setFlash('error','❌ Password minimal 6 karakter.'); header('Location: ?action=edit&id='.$d['_id']); exit; }
      $update .= ",password=?"; $params[] = password_hash($d['password'], PASSWORD_BCRYPT);
    }
    $update .= " WHERE id=?"; $params[] = intval($d['_id']);
    $pdo->prepare($update)->execute($params);
    setFlash('success', '✅ Admin berhasil diperbarui!');
  }
  header('Location: ' . BASE_URL . '/admin/pages/admins.php'); exit;
}

if ($action === 'delete' && $id && $id != $me['id']) {
  $pdo->prepare("DELETE FROM admins WHERE id=?")->execute([$id]);
  setFlash('success', '🗑️ Admin dihapus.'); header('Location: ' . BASE_URL . '/admin/pages/admins.php'); exit;
}

$admin = null;
if ($action === 'edit' && $id) {
  $st = $pdo->prepare("SELECT * FROM admins WHERE id=?"); $st->execute([$id]); $admin = $st->fetch();
}
$admins = $pdo->query("SELECT * FROM admins ORDER BY id")->fetchAll();
$flash = getFlash();
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?> — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head><body>
<div class="admin-layout">
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<div class="admin-main">
<?php include __DIR__ . '/../includes/topbar.php'; ?>
<div class="admin-content">
<?php if($flash): ?><div id="flash-toast" class="flash-toast flash-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div><?php endif; ?>

<?php if($action === 'list'): ?>
<div class="page-header">
  <h1>🔑 Kelola Admin</h1>
  <a href="?action=add" class="btn btn-primary">➕ Tambah Admin</a>
</div>
<div class="admin-card">
  <table>
    <thead><tr><th>Admin</th><th>Username</th><th>Role</th><th>Login Terakhir</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($admins as $a): ?>
      <tr>
        <td>
          <div style="font-weight:600;color:#1e293b"><?= htmlspecialchars($a['name']) ?> <?= $a['id']==$me['id']?'<span style="font-size:11px;background:#dbeafe;color:#1d4ed8;padding:2px 7px;border-radius:100px;font-weight:600">Kamu</span>':'' ?></div>
          <div style="font-size:12px;color:#94a3b8"><?= htmlspecialchars($a['email'] ?? '—') ?></div>
        </td>
        <td style="font-family:monospace;font-size:13px"><?= htmlspecialchars($a['username']) ?></td>
        <td><span class="badge" style="background:<?= $a['role']==='superadmin'?'#fef3c7;color:#b45309':'#f1f5f9;color:#475569' ?>"><?= $a['role'] ?></span></td>
        <td style="font-size:12px;color:#94a3b8"><?= $a['last_login'] ? date('d M Y H:i', strtotime($a['last_login'])) : '—' ?></td>
        <td>
          <div class="table-actions">
            <a href="?action=edit&id=<?= $a['id'] ?>" class="btn btn-ghost btn-sm">✏️ Edit</a>
            <?php if($a['id'] != $me['id']): ?><a href="?action=delete&id=<?= $a['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus admin ini?')">🗑️</a><?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php else: ?>
<div class="page-header">
  <h1><?= $action==='add'?'➕ Tambah Admin':'✏️ Edit Admin' ?></h1>
  <a href="?" class="btn btn-ghost">← Kembali</a>
</div>
<form method="POST">
  <input type="hidden" name="_action" value="<?= $action ?>">
  <?php if($admin): ?><input type="hidden" name="_id" value="<?= $admin['id'] ?>"><?php endif; ?>
  <div class="admin-form" style="max-width:600px">
    <div class="form-grid">
      <div class="fg"><label class="fl">Nama Lengkap *</label><input type="text" name="name" class="fi" required value="<?= htmlspecialchars($admin['name'] ?? '') ?>"></div>
      <div class="fg"><label class="fl">Username *</label><input type="text" name="username" class="fi" required value="<?= htmlspecialchars($admin['username'] ?? '') ?>" <?= $action==='edit'?'readonly':'' ?>></div>
      <div class="fg"><label class="fl">Email</label><input type="email" name="email" class="fi" value="<?= htmlspecialchars($admin['email'] ?? '') ?>"></div>
      <div class="fg"><label class="fl">Role</label>
        <select name="role" class="fs"><option value="admin" <?= ($admin['role']??'')==='admin'?'selected':'' ?>>Admin</option><option value="superadmin" <?= ($admin['role']??'')==='superadmin'?'selected':'' ?>>Superadmin</option></select>
      </div>
      <div class="fg"><label class="fl"><?= $action==='add'?'Password *':'Password Baru (kosong = tidak berubah)' ?></label><input type="password" name="password" class="fi" <?= $action==='add'?'required':'' ?> placeholder="Min. 6 karakter"></div>
    </div>
    <button type="submit" class="btn btn-primary"><?= $action==='add'?'➕ Tambah Admin':'💾 Simpan Perubahan' ?></button>
  </div>
</form>
<?php endif; ?>
</div></div></div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
