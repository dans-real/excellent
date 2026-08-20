<?php
$pageTitle = 'Kelola Anggota';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
$pdo = getDB();
$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = $_POST;
  $photoPath = $d['existing_photo'] ?? '';
  if (!empty($_FILES['photo']['name'])) {
    $up = uploadImage($_FILES['photo'], 'members');
    if ($up['success']) $photoPath = $up['path'];
  }
  $data = [
    'name'       => trim($d['name']),
    'initials'   => strtoupper(trim($d['initials'])),
    'role'       => trim($d['role']),
    'ministry'   => trim($d['ministry']),
    'level'      => $d['level'],
    'color'      => $d['color'],
    'photo'      => $photoPath,
    'bio'        => trim($d['bio'] ?? ''),
    'instagram'  => trim($d['instagram'] ?? ''),
    'sort_order' => intval($d['sort_order']),
    'is_active'  => isset($d['is_active']) ? 1 : 0,
  ];
  if ($d['_action'] === 'add') {
    $cols = implode(',', array_keys($data));
    $phs  = implode(',', array_fill(0, count($data), '?'));
    $pdo->prepare("INSERT INTO members ($cols) VALUES ($phs)")->execute(array_values($data));
    setFlash('success', '✅ Anggota berhasil ditambahkan!');
  } elseif ($d['_action'] === 'edit' && $d['_id']) {
    $sets = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
    $vals = array_values($data); $vals[] = intval($d['_id']);
    $pdo->prepare("UPDATE members SET $sets WHERE id=?")->execute($vals);
    setFlash('success', '✅ Anggota berhasil diperbarui!');
  }
  header('Location: ' . BASE_URL . '/admin/pages/members.php'); exit;
}

if ($action === 'delete' && $id) {
  $m = $pdo->prepare("SELECT photo FROM members WHERE id=?"); $m->execute([$id]);
  $row = $m->fetch(); if ($row && $row['photo']) deleteImage($row['photo']);
  $pdo->prepare("DELETE FROM members WHERE id=?")->execute([$id]);
  setFlash('success', '🗑️ Anggota berhasil dihapus.');
  header('Location: ' . BASE_URL . '/admin/pages/members.php'); exit;
}

$member = null;
if ($action === 'edit' && $id) {
  $s2 = $pdo->prepare("SELECT * FROM members WHERE id=?"); $s2->execute([$id]); $member = $s2->fetch();
  if (!$member) { header('Location: ' . BASE_URL . '/admin/pages/members.php'); exit; }
}
$members = $pdo->query("SELECT * FROM members ORDER BY sort_order, id")->fetchAll();
$flash = getFlash();
$levels   = ['leader'=>'Ketua Umum/Pimpinan','core'=>'Core/Sekretariat','minister'=>'Menteri','staff'=>'Staff'];
$ministries = ['General Government','Ministry of Education','Ministry of Entrepreneurship','Ministry of Public Relation','Ministry of Human Resource'];
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
  <h1>👥 Kelola Anggota</h1>
  <a href="?action=add" class="btn btn-primary">➕ Tambah Anggota</a>
</div>
<div class="admin-card">
  <table>
    <thead><tr><th>Anggota</th><th>Jabatan</th><th>Kementerian</th><th>Level</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($members as $m): ?>
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:.75rem">
            <div class="avatar-circle" style="background:<?= htmlspecialchars($m['color']) ?>">
              <?php if($m['photo']): ?><img src="<?= UPLOAD_URL . htmlspecialchars($m['photo']) ?>" alt=""><?php else: ?><?= htmlspecialchars($m['initials']) ?><?php endif; ?>
            </div>
            <div>
              <div style="font-weight:600;color:#1e293b"><?= htmlspecialchars($m['name']) ?></div>
              <?php if($m['instagram']): ?><div style="font-size:11px;color:#94a3b8">@<?= htmlspecialchars($m['instagram']) ?></div><?php endif; ?>
            </div>
          </div>
        </td>
        <td><?= htmlspecialchars($m['role']) ?></td>
        <td style="font-size:12px"><?= htmlspecialchars($m['ministry']) ?></td>
        <td><span class="badge" style="background:#f1f5f9;color:#475569"><?= htmlspecialchars($m['level']) ?></span></td>
        <td><?= $m['is_active'] ? '<span class="badge badge-open">Aktif</span>' : '<span class="badge badge-closed">Nonaktif</span>' ?></td>
        <td>
          <div class="table-actions">
            <a href="?action=edit&id=<?= $m['id'] ?>" class="btn btn-ghost btn-sm">✏️ Edit</a>
            <a href="?action=delete&id=<?= $m['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus anggota ini?')">🗑️</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php else: ?>
<div class="page-header">
  <h1><?= $action==='add' ? '➕ Tambah Anggota' : '✏️ Edit Anggota' ?></h1>
  <a href="?" class="btn btn-ghost">← Kembali</a>
</div>
<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="_action" value="<?= $action ?>">
  <?php if($member): ?><input type="hidden" name="_id" value="<?= $member['id'] ?>"><input type="hidden" name="existing_photo" value="<?= htmlspecialchars($member['photo'] ?? '') ?>"><?php endif; ?>
  <div class="admin-form">
    <div class="form-grid">
      <div class="fg"><label class="fl">Nama Lengkap *</label><input type="text" name="name" class="fi" required value="<?= htmlspecialchars($member['name'] ?? '') ?>" placeholder="Ahmad Rizqullah"></div>
      <div class="fg"><label class="fl">Inisial (2-3 huruf)</label><input type="text" name="initials" class="fi" maxlength="3" value="<?= htmlspecialchars($member['initials'] ?? '') ?>" placeholder="AR"></div>
      <div class="fg"><label class="fl">Jabatan *</label><input type="text" name="role" class="fi" required value="<?= htmlspecialchars($member['role'] ?? '') ?>" placeholder="Ketua Umum"></div>
      <div class="fg"><label class="fl">Level</label>
        <select name="level" class="fs"><?php foreach($levels as $k=>$v): ?><option value="<?= $k ?>" <?= ($member['level']??'')===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
      </div>
      <div class="fg"><label class="fl">Kementerian / Divisi</label>
        <select name="ministry" class="fs"><?php foreach($ministries as $mn): ?><option value="<?= $mn ?>" <?= ($member['ministry']??'')===$mn?'selected':'' ?>><?= $mn ?></option><?php endforeach; ?></select>
      </div>
      <div class="fg"><label class="fl">Warna Avatar</label><input type="color" name="color" value="<?= htmlspecialchars($member['color'] ?? '#1E3A5F') ?>"></div>
      <div class="fg"><label class="fl">Instagram (tanpa @)</label><input type="text" name="instagram" class="fi" value="<?= htmlspecialchars($member['instagram'] ?? '') ?>" placeholder="namauser"></div>
      <div class="fg"><label class="fl">Urutan Tampil</label><input type="number" name="sort_order" class="fi" value="<?= htmlspecialchars($member['sort_order'] ?? '0') ?>"></div>
    </div>
    <div class="fg">
      <label class="fl">Foto Profil</label>
      <?php if(!empty($member['photo'])): ?>
      <img src="<?= UPLOAD_URL . htmlspecialchars($member['photo']) ?>" class="img-preview" style="display:block;margin-bottom:.5rem"><small style="color:#94a3b8">Upload baru untuk mengganti</small><br>
      <?php endif; ?>
      <input type="file" name="photo" accept="image/*" class="fi" style="padding:.3rem">
    </div>
    <div class="fg"><label class="fl">Bio Singkat</label><textarea name="bio" class="ft" rows="3" placeholder="Deskripsi singkat..."><?= htmlspecialchars($member['bio'] ?? '') ?></textarea></div>
    <div class="fg" style="display:flex;align-items:center;gap:.5rem">
      <input type="checkbox" name="is_active" id="is_active" <?= ($member['is_active'] ?? 1) ? 'checked' : '' ?>>
      <label for="is_active" class="fl" style="margin:0">Anggota Aktif</label>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top:.5rem"><?= $action==='add' ? '➕ Simpan Anggota' : '💾 Perbarui Anggota' ?></button>
  </div>
</form>
<?php endif; ?>
</div></div></div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
