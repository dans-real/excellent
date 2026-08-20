<?php
$pageTitle = 'Kelola Gallery';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
$pdo = getDB();
$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = $_POST;
  if ($d['_action'] === 'add') {
    if (empty($_FILES['image']['name'])) {
      setFlash('error', '❌ Pilih foto untuk diupload.'); header('Location: ' . BASE_URL . '/admin/pages/gallery.php?action=add'); exit;
    }
    $up = uploadImage($_FILES['image'], 'gallery');
    if (!$up['success']) { setFlash('error', '❌ ' . $up['error']); header('Location: ' . BASE_URL . '/admin/pages/gallery.php?action=add'); exit; }
    $pdo->prepare("INSERT INTO gallery (title,description,image_path,category,event_id,sort_order,is_featured) VALUES (?,?,?,?,?,?,?)")
      ->execute([$d['title'], $d['description'], $up['path'], $d['category'], $d['event_id'] ?: null, intval($d['sort_order']), isset($d['is_featured']) ? 1 : 0]);
    setFlash('success', '✅ Foto berhasil diupload!');
  } elseif ($d['_action'] === 'edit' && $d['_id']) {
    $imgPath = $d['existing_image'];
    if (!empty($_FILES['image']['name'])) {
      $up = uploadImage($_FILES['image'], 'gallery');
      if ($up['success']) { deleteImage($imgPath); $imgPath = $up['path']; }
    }
    $pdo->prepare("UPDATE gallery SET title=?,description=?,image_path=?,category=?,event_id=?,sort_order=?,is_featured=? WHERE id=?")
      ->execute([$d['title'], $d['description'], $imgPath, $d['category'], $d['event_id'] ?: null, intval($d['sort_order']), isset($d['is_featured']) ? 1 : 0, intval($d['_id'])]);
    setFlash('success', '✅ Foto berhasil diperbarui!');
  }
  header('Location: ' . BASE_URL . '/admin/pages/gallery.php'); exit;
}

if ($action === 'delete' && $id) {
  $row = $pdo->prepare("SELECT image_path FROM gallery WHERE id=?"); $row->execute([$id]); $row = $row->fetch();
  if ($row) deleteImage($row['image_path']);
  $pdo->prepare("DELETE FROM gallery WHERE id=?")->execute([$id]);
  setFlash('success', '🗑️ Foto berhasil dihapus.');
  header('Location: ' . BASE_URL . '/admin/pages/gallery.php'); exit;
}

$item = null;
if ($action === 'edit' && $id) {
  $st = $pdo->prepare("SELECT * FROM gallery WHERE id=?"); $st->execute([$id]); $item = $st->fetch();
  if (!$item) { header('Location: ' . BASE_URL . '/admin/pages/gallery.php'); exit; }
}
$galleries = $pdo->query("SELECT g.*, e.title as event_title FROM gallery g LEFT JOIN events e ON g.event_id=e.id ORDER BY g.sort_order, g.id DESC")->fetchAll();
$events = $pdo->query("SELECT id,title FROM events ORDER BY sort_order")->fetchAll();
$cats = ['Umum','GACO','English Week','CASE Competition','Seminar','Internal','Kegiatan Sosial','Lainnya'];
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
  <h1>🖼️ Kelola Gallery</h1>
  <a href="?action=add" class="btn btn-primary">📷 Upload Foto</a>
</div>

<!-- GRID PREVIEW -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem">
  <?php foreach($galleries as $g): ?>
  <div style="background:#fff;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.05)">
    <div style="height:140px;overflow:hidden;position:relative">
      <img src="<?= UPLOAD_URL . htmlspecialchars($g['image_path']) ?>" alt="<?= htmlspecialchars($g['title']) ?>" style="width:100%;height:100%;object-fit:cover">
      <?php if($g['is_featured']): ?><span style="position:absolute;top:.4rem;left:.4rem;background:var(--gold);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px">⭐ Featured</span><?php endif; ?>
    </div>
    <div style="padding:.75rem">
      <div style="font-weight:600;font-size:13px;color:#1e293b;margin-bottom:.25rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($g['title']) ?></div>
      <div style="font-size:11px;color:#94a3b8;margin-bottom:.5rem"><?= htmlspecialchars($g['category']) ?></div>
      <div style="display:flex;gap:.35rem">
        <a href="?action=edit&id=<?= $g['id'] ?>" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center">✏️</a>
        <a href="?action=delete&id=<?= $g['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus foto ini?')">🗑️</a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($galleries)): ?>
  <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#94a3b8"><div style="font-size:3rem;margin-bottom:1rem">📷</div><p>Belum ada foto. Mulai upload!</p></div>
  <?php endif; ?>
</div>

<?php else: ?>
<div class="page-header">
  <h1><?= $action==='add' ? '📷 Upload Foto' : '✏️ Edit Foto' ?></h1>
  <a href="?" class="btn btn-ghost">← Kembali</a>
</div>
<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="_action" value="<?= $action ?>">
  <?php if($item): ?><input type="hidden" name="_id" value="<?= $item['id'] ?>"><input type="hidden" name="existing_image" value="<?= htmlspecialchars($item['image_path'] ?? '') ?>"><?php endif; ?>
  <div class="admin-form">
    <div class="form-grid">
      <div class="fg"><label class="fl">Judul Foto *</label><input type="text" name="title" class="fi" required value="<?= htmlspecialchars($item['title'] ?? '') ?>" placeholder="GACO 2024 — Grand Final"></div>
      <div class="fg"><label class="fl">Kategori</label>
        <select name="category" class="fs"><?php foreach($cats as $c): ?><option value="<?= $c ?>" <?= ($item['category']??'Umum')===$c?'selected':'' ?>><?= $c ?></option><?php endforeach; ?></select>
      </div>
      <div class="fg"><label class="fl">Event Terkait (opsional)</label>
        <select name="event_id" class="fs"><option value="">— Tidak ada —</option>
          <?php foreach($events as $ev): ?><option value="<?= $ev['id'] ?>" <?= ($item['event_id']??0)==$ev['id']?'selected':'' ?>><?= htmlspecialchars($ev['title']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="fg"><label class="fl">Urutan Tampil</label><input type="number" name="sort_order" class="fi" value="<?= htmlspecialchars($item['sort_order'] ?? '0') ?>"></div>
    </div>
    <div class="fg"><label class="fl">Deskripsi</label><textarea name="description" class="ft" rows="3" placeholder="Keterangan foto..."><?= htmlspecialchars($item['description'] ?? '') ?></textarea></div>
    <div class="fg">
      <label class="fl"><?= $action==='add' ? 'File Foto *' : 'Ganti Foto (kosongkan jika tidak diganti)' ?></label>
      <?php if(!empty($item['image_path'])): ?>
      <img src="<?= UPLOAD_URL . htmlspecialchars($item['image_path']) ?>" class="img-preview" style="display:block;margin-bottom:.5rem;width:160px;height:100px">
      <?php endif; ?>
      <input type="file" name="image" accept="image/*" class="fi" style="padding:.3rem" <?= $action==='add'?'required':'' ?>>
      <small style="color:#94a3b8;display:block;margin-top:.25rem">Format: JPG, PNG, WebP. Maks 5MB.</small>
    </div>
    <div class="fg" style="display:flex;align-items:center;gap:.5rem">
      <input type="checkbox" name="is_featured" id="feat" <?= ($item['is_featured'] ?? 0) ? 'checked' : '' ?>>
      <label for="feat" class="fl" style="margin:0">⭐ Tampilkan sebagai Featured</label>
    </div>
    <button type="submit" class="btn btn-primary"><?= $action==='add' ? '📷 Upload Foto' : '💾 Perbarui Foto' ?></button>
  </div>
</form>
<?php endif; ?>
</div></div></div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
