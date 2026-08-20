<?php
$pageTitle = 'Pesan Masuk';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
$pdo = getDB();

// Mark as read
if (isset($_GET['read'])) {
  $pdo->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([intval($_GET['read'])]);
  header('Location: ' . BASE_URL . '/admin/pages/messages.php'); exit;
}
// Delete
if (isset($_GET['delete'])) {
  $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([intval($_GET['delete'])]);
  setFlash('success', '🗑️ Pesan dihapus.'); header('Location: ' . BASE_URL . '/admin/pages/messages.php'); exit;
}
// Mark all read
if (isset($_GET['readall'])) {
  $pdo->query("UPDATE messages SET is_read=1"); header('Location: ' . BASE_URL . '/admin/pages/messages.php'); exit;
}

$filter = $_GET['filter'] ?? 'all';
$where = $filter === 'unread' ? 'WHERE is_read=0' : '';
$messages = $pdo->query("SELECT * FROM messages $where ORDER BY created_at DESC")->fetchAll();
$unreadCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
$flash = getFlash();
$detail = null;
if (isset($_GET['view'])) {
  $st = $pdo->prepare("SELECT * FROM messages WHERE id=?"); $st->execute([intval($_GET['view'])]); $detail = $st->fetch();
  if ($detail && !$detail['is_read']) $pdo->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([$detail['id']]);
}
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

<?php if($detail): ?>
<!-- DETAIL VIEW -->
<div class="page-header">
  <h1>✉️ Detail Pesan</h1>
  <a href="?" class="btn btn-ghost">← Kembali</a>
</div>
<div class="admin-card" style="padding:2rem;max-width:700px">
  <div style="display:flex;gap:1rem;align-items:start;margin-bottom:1.5rem">
    <div style="width:48px;height:48px;background:var(--navy);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:18px;flex-shrink:0"><?= strtoupper(substr($detail['name'],0,1)) ?></div>
    <div>
      <div style="font-weight:700;font-size:16px;color:#1e293b"><?= htmlspecialchars($detail['name']) ?></div>
      <div style="font-size:13px;color:#64748b"><?= htmlspecialchars($detail['email']) ?></div>
      <?php if($detail['university']): ?><div style="font-size:12px;color:#94a3b8">🏛️ <?= htmlspecialchars($detail['university']) ?></div><?php endif; ?>
    </div>
    <div style="margin-left:auto;font-size:12px;color:#94a3b8"><?= date('d M Y H:i', strtotime($detail['created_at'])) ?></div>
  </div>
  <div style="background:#f8fafc;border-radius:8px;padding:1rem;margin-bottom:1rem">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.4rem">Subjek</div>
    <div style="font-weight:600;color:#1e293b"><?= htmlspecialchars($detail['subject'] ?? 'Pertanyaan Umum') ?></div>
  </div>
  <div style="background:#f8fafc;border-radius:8px;padding:1rem">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.4rem">Pesan</div>
    <div style="color:#334155;line-height:1.75;white-space:pre-wrap"><?= htmlspecialchars($detail['message']) ?></div>
  </div>
  <div style="display:flex;gap:.75rem;margin-top:1.5rem">
    <a href="mailto:<?= htmlspecialchars($detail['email']) ?>" class="btn btn-primary">📧 Balas via Email</a>
    <a href="?delete=<?= $detail['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus pesan ini?')">🗑️ Hapus</a>
  </div>
</div>

<?php else: ?>
<!-- LIST -->
<div class="page-header">
  <h1>✉️ Pesan Masuk <?php if($unreadCount): ?><span class="badge badge-open" style="font-size:12px;vertical-align:middle"><?= $unreadCount ?> baru</span><?php endif; ?></h1>
  <div style="display:flex;gap:.5rem">
    <a href="?filter=<?= $filter==='unread'?'all':'unread' ?>" class="btn btn-ghost"><?= $filter==='unread'?'Tampilkan Semua':'Belum Dibaca' ?></a>
    <?php if($unreadCount): ?><a href="?readall=1" class="btn btn-ghost">✓ Tandai Semua Dibaca</a><?php endif; ?>
  </div>
</div>
<div class="admin-card">
  <?php if(empty($messages)): ?>
  <div style="text-align:center;padding:3rem;color:#94a3b8"><div style="font-size:3rem;margin-bottom:1rem">📭</div><p>Tidak ada pesan.</p></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Pengirim</th><th>Subjek</th><th>Universitas</th><th>Waktu</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($messages as $msg): ?>
      <tr style="<?= !$msg['is_read'] ? 'background:#fafff5' : '' ?>">
        <td>
          <div style="font-weight:<?= $msg['is_read']?'500':'700' ?>;color:#1e293b"><?= htmlspecialchars($msg['name']) ?></div>
          <div style="font-size:11px;color:#94a3b8"><?= htmlspecialchars($msg['email']) ?></div>
        </td>
        <td style="font-size:13px">
          <?= !$msg['is_read'] ? '<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#22c55e;margin-right:.4rem"></span>' : '' ?>
          <?= htmlspecialchars(substr($msg['subject'] ?? 'Umum', 0, 40)) ?>
        </td>
        <td style="font-size:12px;color:#64748b"><?= htmlspecialchars($msg['university'] ?? '—') ?></td>
        <td style="font-size:12px;color:#94a3b8;white-space:nowrap"><?= timeAgo($msg['created_at']) ?></td>
        <td>
          <div class="table-actions">
            <a href="?view=<?= $msg['id'] ?>" class="btn btn-ghost btn-sm">👁️ Baca</a>
            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-ghost btn-sm">📧</a>
            <a href="?delete=<?= $msg['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">🗑️</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php endif; ?>
</div></div></div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
