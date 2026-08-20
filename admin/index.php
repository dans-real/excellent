<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$pdo = getDB();
$stats = [
  'events'   => $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn(),
  'members'  => $pdo->query("SELECT COUNT(*) FROM members WHERE is_active=1")->fetchColumn(),
  'gallery'  => $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn(),
  'messages' => $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn(),
];
$latestEvents   = $pdo->query("SELECT * FROM events ORDER BY created_at DESC LIMIT 5")->fetchAll();
$latestMessages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?> — Admin EXCELLENT</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head><body>
<div class="admin-layout">
  <?php include __DIR__ . '/includes/sidebar.php'; ?>
  <div class="admin-main">
    <?php include __DIR__ . '/includes/topbar.php'; ?>
    <div class="admin-content">
      <!-- STATS -->
      <div class="stat-grid">
        <?php $statData = [
          ['📅','Events','events','#dbeafe','#1d4ed8'],
          ['👥','Anggota Aktif','members','#dcfce7','#166534'],
          ['🖼️','Foto Gallery','gallery','#fef9c3','#b45309'],
          ['✉️','Pesan Belum Dibaca','messages','#fee2e2','#dc2626'],
        ]; foreach($statData as [$icon,$label,$key,$bg,$color]): ?>
        <div class="stat-card">
          <div class="stat-card-icon" style="background:<?= $bg ?>;color:<?= $color ?>"><?= $icon ?></div>
          <div class="stat-card-num"><?= $stats[$key] ?></div>
          <div class="stat-card-label"><?= $label ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
        <!-- LATEST EVENTS -->
        <div class="admin-card">
          <div class="admin-card-header">
            <div class="admin-card-title">Event Terbaru</div>
            <a href="<?= BASE_URL ?>/admin/pages/events.php" class="btn btn-ghost btn-sm">Lihat Semua</a>
          </div>
          <table>
            <thead><tr><th>Event</th><th>Kategori</th><th>Status</th></tr></thead>
            <tbody>
              <?php foreach($latestEvents as $ev): ?>
              <tr>
                <td><div style="font-weight:600;color:#1e293b"><?= htmlspecialchars(substr($ev['title'],0,35)) ?>...</div><div style="font-size:11px;color:#94a3b8"><?= htmlspecialchars($ev['event_date']) ?></div></td>
                <td><span class="badge badge-<?= $ev['category'] ?>"><?= ucfirst($ev['category']) ?></span></td>
                <td><span class="badge badge-<?= $ev['status'] ?>"><?= ucfirst($ev['status']) ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- LATEST MESSAGES -->
        <div class="admin-card">
          <div class="admin-card-header">
            <div class="admin-card-title">Pesan Terbaru</div>
            <a href="<?= BASE_URL ?>/admin/pages/messages.php" class="btn btn-ghost btn-sm">Lihat Semua</a>
          </div>
          <table>
            <thead><tr><th>Nama</th><th>Subjek</th><th>Waktu</th></tr></thead>
            <tbody>
              <?php foreach($latestMessages as $msg): ?>
              <tr>
                <td><div style="font-weight:<?= $msg['is_read']?'400':'700' ?>;color:#1e293b"><?= htmlspecialchars($msg['name']) ?></div><div style="font-size:11px;color:#94a3b8"><?= htmlspecialchars($msg['email']) ?></div></td>
                <td style="font-size:12px"><?= htmlspecialchars(substr($msg['subject'] ?? 'Umum',0,30)) ?></td>
                <td style="font-size:11px;color:#94a3b8;white-space:nowrap"><?= timeAgo($msg['created_at']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <div class="admin-card" style="margin-top:1.5rem">
        <div class="admin-card-header"><div class="admin-card-title">Quick Actions</div></div>
        <div style="padding:1rem;display:flex;gap:.75rem;flex-wrap:wrap">
          <a href="<?= BASE_URL ?>/admin/pages/events.php?action=add" class="btn btn-primary">➕ Tambah Event</a>
          <a href="<?= BASE_URL ?>/admin/pages/members.php?action=add" class="btn btn-gold">➕ Tambah Anggota</a>
          <a href="<?= BASE_URL ?>/admin/pages/gallery.php?action=add" class="btn btn-ghost">📷 Upload Foto</a>
          <a href="<?= BASE_URL ?>/admin/pages/settings.php" class="btn btn-ghost">⚙️ Pengaturan</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
