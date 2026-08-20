<?php
$pageTitle = 'Kelola Events';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
$pdo = getDB();
$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);
$msg = ''; $msgType = '';

// HANDLE POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = $_POST;
  $branches = array_filter(array_map('trim', explode("\n", $d['branches'] ?? '')));
  $timelineRaw = array_filter(array_map('trim', explode("\n", $d['timeline'] ?? '')));
  $timeline = [];
  foreach ($timelineRaw as $line) {
    $parts = explode('|', $line, 2);
    if (count($parts) === 2) $timeline[] = ['date' => trim($parts[0]), 'label' => trim($parts[1])];
  }
  $data = [
    'title' => $d['title'], 'slug' => $d['slug'] ?: slugify($d['title']),
    'category' => $d['category'], 'status' => $d['status'],
    'event_date' => $d['event_date'], 'location' => $d['location'],
    'description' => $d['description'], 'lead' => $d['lead'],
    'prize' => $d['prize'], 'fee' => $d['fee'], 'capacity' => $d['capacity'],
    'emoji' => $d['emoji'] ?: '📅', 'gradient_from' => $d['gradient_from'],
    'gradient_to' => $d['gradient_to'], 'is_featured' => isset($d['is_featured']) ? 1 : 0,
    'sort_order' => intval($d['sort_order']),
    'branches' => json_encode(array_values($branches)),
    'timeline' => json_encode($timeline),
  ];

  if ($d['_action'] === 'add') {
    $cols = implode(',', array_keys($data));
    $phs = implode(',', array_fill(0, count($data), '?'));
    $pdo->prepare("INSERT INTO events ($cols) VALUES ($phs)")->execute(array_values($data));
    setFlash('success', '✅ Event berhasil ditambahkan!');
  } elseif ($d['_action'] === 'edit' && $d['_id']) {
    $sets = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
    $vals = array_values($data); $vals[] = intval($d['_id']);
    $pdo->prepare("UPDATE events SET $sets WHERE id=?")->execute($vals);
    setFlash('success', '✅ Event berhasil diperbarui!');
  }
  header('Location: ' . BASE_URL . '/admin/pages/events.php'); exit;
}

// DELETE
if ($action === 'delete' && $id) {
  $pdo->prepare("DELETE FROM events WHERE id=?")->execute([$id]);
  setFlash('success', '🗑️ Event berhasil dihapus.');
  header('Location: ' . BASE_URL . '/admin/pages/events.php'); exit;
}

$event = null;
if (($action === 'edit') && $id) {
  $event = $pdo->prepare("SELECT * FROM events WHERE id=?");
  $event->execute([$id]); $event = $event->fetch();
  if (!$event) { header('Location: ' . BASE_URL . '/admin/pages/events.php'); exit; }
}
$events = $pdo->query("SELECT * FROM events ORDER BY sort_order, id DESC")->fetchAll();
$flash = getFlash();
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?> — Admin EXCELLENT</title>
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
<!-- LIST -->
<div class="page-header">
  <h1>📅 Kelola Events</h1>
  <a href="?action=add" class="btn btn-primary">➕ Tambah Event</a>
</div>
<div class="admin-card">
  <table>
    <thead><tr><th>Event</th><th>Kategori</th><th>Status</th><th>Featured</th><th>Urutan</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($events as $ev): ?>
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:.75rem">
            <div style="width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;background:linear-gradient(135deg,<?= htmlspecialchars($ev['gradient_from']) ?>,<?= htmlspecialchars($ev['gradient_to']) ?>)"><?= htmlspecialchars($ev['emoji']) ?></div>
            <div><div style="font-weight:600;color:#1e293b"><?= htmlspecialchars($ev['title']) ?></div><div style="font-size:11px;color:#94a3b8"><?= htmlspecialchars($ev['event_date']) ?> · <?= htmlspecialchars($ev['location']) ?></div></div>
          </div>
        </td>
        <td><span class="badge badge-<?= $ev['category'] ?>"><?= ucfirst($ev['category']) ?></span></td>
        <td><span class="badge badge-<?= $ev['status'] ?>"><?= ucfirst($ev['status']) ?></span></td>
        <td><?= $ev['is_featured'] ? '⭐' : '—' ?></td>
        <td><?= $ev['sort_order'] ?></td>
        <td>
          <div class="table-actions">
            <a href="<?= BASE_URL ?>/event-detail.php?slug=<?= urlencode($ev['slug']) ?>" target="_blank" class="btn btn-ghost btn-sm">👁️</a>
            <a href="?action=edit&id=<?= $ev['id'] ?>" class="btn btn-ghost btn-sm">✏️ Edit</a>
            <a href="?action=delete&id=<?= $ev['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus event ini?')">🗑️</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php else: ?>
<!-- ADD / EDIT FORM -->
<div class="page-header">
  <h1><?= $action==='add' ? '➕ Tambah Event' : '✏️ Edit Event' ?></h1>
  <a href="?" class="btn btn-ghost">← Kembali</a>
</div>
<form method="POST">
  <input type="hidden" name="_action" value="<?= $action ?>">
  <?php if($event): ?><input type="hidden" name="_id" value="<?= $event['id'] ?>"><?php endif; ?>
  <div class="admin-form">
    <div class="form-grid">
      <div class="fg"><label class="fl">Judul Event *</label><input type="text" name="title" class="fi" required value="<?= htmlspecialchars($event['title'] ?? '') ?>" placeholder="Galaxy Competition 2025"></div>
      <div class="fg"><label class="fl">Slug (URL)</label><input type="text" name="slug" class="fi" value="<?= htmlspecialchars($event['slug'] ?? '') ?>" placeholder="galaxy-competition-2025"></div>
      <div class="fg"><label class="fl">Kategori</label>
        <select name="category" class="fs"><?php foreach(['competition','seminar','workshop','internal'] as $c): ?><option value="<?= $c ?>" <?= ($event['category'] ?? '')===$c?'selected':'' ?>><?= ucfirst($c) ?></option><?php endforeach; ?></select>
      </div>
      <div class="fg"><label class="fl">Status</label>
        <select name="status" class="fs"><?php foreach(['open','upcoming','closed'] as $c): ?><option value="<?= $c ?>" <?= ($event['status'] ?? 'upcoming')===$c?'selected':'' ?>><?= ucfirst($c) ?></option><?php endforeach; ?></select>
      </div>
      <div class="fg"><label class="fl">Tanggal</label><input type="text" name="event_date" class="fi" value="<?= htmlspecialchars($event['event_date'] ?? '') ?>" placeholder="Juni 2025"></div>
      <div class="fg"><label class="fl">Lokasi</label><input type="text" name="location" class="fi" value="<?= htmlspecialchars($event['location'] ?? '') ?>" placeholder="Online & Offline · Nasional"></div>
      <div class="fg"><label class="fl">Emoji</label><input type="text" name="emoji" class="fi" value="<?= htmlspecialchars($event['emoji'] ?? '📅') ?>" placeholder="🏆" style="width:80px"></div>
      <div class="fg"><label class="fl">Urutan</label><input type="number" name="sort_order" class="fi" value="<?= htmlspecialchars($event['sort_order'] ?? '0') ?>"></div>
      <div class="fg"><label class="fl">Warna Dari (gradient)</label><input type="color" name="gradient_from" value="<?= htmlspecialchars($event['gradient_from'] ?? '#1E3A5F') ?>"></div>
      <div class="fg"><label class="fl">Warna Ke (gradient)</label><input type="color" name="gradient_to" value="<?= htmlspecialchars($event['gradient_to'] ?? '#2a5f9e') ?>"></div>
      <div class="fg"><label class="fl">Hadiah</label><input type="text" name="prize" class="fi" value="<?= htmlspecialchars($event['prize'] ?? '') ?>" placeholder="Rp 10.000.000"></div>
      <div class="fg"><label class="fl">Biaya Pendaftaran</label><input type="text" name="fee" class="fi" value="<?= htmlspecialchars($event['fee'] ?? '') ?>" placeholder="Rp 50.000 / Gratis"></div>
      <div class="fg"><label class="fl">Kapasitas</label><input type="text" name="capacity" class="fi" value="<?= htmlspecialchars($event['capacity'] ?? '') ?>" placeholder="200 Peserta"></div>
      <div class="fg" style="display:flex;align-items:center;gap:.5rem;padding-top:1.5rem">
        <input type="checkbox" name="is_featured" id="featured" <?= ($event['is_featured'] ?? 0) ? 'checked' : '' ?>>
        <label for="featured" class="fl" style="margin:0">⭐ Tampilkan di Beranda</label>
      </div>
    </div>
    <div class="fg"><label class="fl">Deskripsi Singkat</label><textarea name="description" class="ft" placeholder="Deskripsi singkat event..."><?= htmlspecialchars($event['description'] ?? '') ?></textarea></div>
    <div class="fg"><label class="fl">Lead Text (intro halaman detail)</label><textarea name="lead" class="ft" placeholder="Teks pengantar di halaman detail event..."><?= htmlspecialchars($event['lead'] ?? '') ?></textarea></div>
    <div class="fg"><label class="fl">Cabang Lomba (1 item per baris)</label><textarea name="branches" class="ft" rows="5" placeholder="English Debate — British Parliamentary&#10;English Speech&#10;English Story Telling"><?= htmlspecialchars(implode("\n", json_decode($event['branches'] ?? '[]', true) ?: [])) ?></textarea></div>
    <div class="fg"><label class="fl">Timeline (format: Tanggal|Keterangan, 1 per baris)</label><textarea name="timeline" class="ft" rows="5" placeholder="April–Mei 2025|Open Registration&#10;Mei 2025|Technical Meeting&#10;Juni 2025|Grand Final"><?php
      $tl = json_decode($event['timeline'] ?? '[]', true) ?: [];
      echo htmlspecialchars(implode("\n", array_map(fn($t) => ($t['date'] ?? '') . '|' . ($t['label'] ?? ''), $tl)));
    ?></textarea></div>
    <button type="submit" class="btn btn-primary"><?= $action==='add' ? '➕ Simpan Event' : '💾 Perbarui Event' ?></button>
  </div>
</form>
<?php endif; ?>
</div></div></div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
