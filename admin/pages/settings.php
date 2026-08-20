<?php
$pageTitle = 'Pengaturan Beranda';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $keys = [
    'hero_badge','hero_title','hero_subtitle','stat_alumni','stat_events','stat_tahun',
    'about_title','about_desc','visi','misi',
    'contact_address','contact_whatsapp','contact_email','contact_website',
    'social_instagram','social_youtube','social_tiktok','social_whatsapp',
    'footer_tagline','site_name','year_founded'
  ];
  $stmt = $pdo->prepare("INSERT INTO site_settings (key_name,value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=?");
  foreach ($keys as $k) {
    $val = trim($_POST[$k] ?? '');
    $stmt->execute([$k, $val, $val]);
  }
  setFlash('success', '✅ Pengaturan berhasil disimpan!');
  header('Location: ' . BASE_URL . '/admin/pages/settings.php'); exit;
}

$s = getAllSettings();
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

<div class="page-header">
  <h1>⚙️ Pengaturan Beranda</h1>
  <a href="<?= BASE_URL ?>" target="_blank" class="btn btn-ghost">🌐 Lihat Website</a>
</div>

<form method="POST">
  <!-- HERO -->
  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header"><div class="admin-card-title">🦸 Hero Section</div></div>
    <div style="padding:1.5rem">
      <div class="form-grid">
        <div class="fg"><label class="fl">Badge Text</label><input type="text" name="hero_badge" class="fi" value="<?= htmlspecialchars($s['hero_badge'] ?? '') ?>" placeholder="UKM-F EXCELLENT — FIP"></div>
        <div class="fg"><label class="fl">Nama Website</label><input type="text" name="site_name" class="fi" value="<?= htmlspecialchars($s['site_name'] ?? '') ?>"></div>
        <div class="fg"><label class="fl">Tahun Berdiri</label><input type="text" name="year_founded" class="fi" value="<?= htmlspecialchars($s['year_founded'] ?? '2015') ?>"></div>
        <div></div>
        <div class="fg"><label class="fl">Jumlah Alumni</label><input type="text" name="stat_alumni" class="fi" value="<?= htmlspecialchars($s['stat_alumni'] ?? '500') ?>"></div>
        <div class="fg"><label class="fl">Jumlah Event/Tahun</label><input type="text" name="stat_events" class="fi" value="<?= htmlspecialchars($s['stat_events'] ?? '12') ?>"></div>
        <div class="fg"><label class="fl">Tahun (counter)</label><input type="text" name="stat_tahun" class="fi" value="<?= htmlspecialchars($s['stat_tahun'] ?? '2015') ?>"></div>
      </div>
      <div class="fg"><label class="fl">Judul Hero (pisahkan baris dengan koma)</label><input type="text" name="hero_title" class="fi" value="<?= htmlspecialchars($s['hero_title'] ?? '') ?>" placeholder="From Zero to Hero, Together."></div>
      <div class="fg"><label class="fl">Subtitle / Deskripsi Hero</label><textarea name="hero_subtitle" class="ft" rows="3"><?= htmlspecialchars($s['hero_subtitle'] ?? '') ?></textarea></div>
    </div>
  </div>

  <!-- ABOUT -->
  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header"><div class="admin-card-title">ℹ️ Tentang Kami</div></div>
    <div style="padding:1.5rem">
      <div class="fg"><label class="fl">Judul About</label><input type="text" name="about_title" class="fi" value="<?= htmlspecialchars($s['about_title'] ?? '') ?>"></div>
      <div class="fg"><label class="fl">Deskripsi Singkat</label><textarea name="about_desc" class="ft" rows="3"><?= htmlspecialchars($s['about_desc'] ?? '') ?></textarea></div>
      <div class="fg"><label class="fl">Visi</label><textarea name="visi" class="ft" rows="4"><?= htmlspecialchars($s['visi'] ?? '') ?></textarea></div>
      <div class="fg"><label class="fl">Misi (satu poin per baris)</label><textarea name="misi" class="ft" rows="6" placeholder="Menyelenggarakan program...&#10;Memfasilitasi mahasiswa..."><?= htmlspecialchars($s['misi'] ?? '') ?></textarea></div>
    </div>
  </div>

  <!-- CONTACT -->
  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header"><div class="admin-card-title">📞 Kontak & Alamat</div></div>
    <div style="padding:1.5rem">
      <div class="form-grid">
        <div class="fg"><label class="fl">Alamat</label><input type="text" name="contact_address" class="fi" value="<?= htmlspecialchars($s['contact_address'] ?? '') ?>"></div>
        <div class="fg"><label class="fl">Nomor WhatsApp</label><input type="text" name="contact_whatsapp" class="fi" value="<?= htmlspecialchars($s['contact_whatsapp'] ?? '') ?>" placeholder="+62 895-xxx-xxx-xxx"></div>
        <div class="fg"><label class="fl">Email</label><input type="email" name="contact_email" class="fi" value="<?= htmlspecialchars($s['contact_email'] ?? '') ?>"></div>
        <div class="fg"><label class="fl">Website</label><input type="text" name="contact_website" class="fi" value="<?= htmlspecialchars($s['contact_website'] ?? '') ?>"></div>
      </div>
    </div>
  </div>

  <!-- SOCIAL MEDIA -->
  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header"><div class="admin-card-title">📱 Media Sosial</div></div>
    <div style="padding:1.5rem">
      <div class="form-grid">
        <div class="fg"><label class="fl">📷 Instagram URL</label><input type="url" name="social_instagram" class="fi" value="<?= htmlspecialchars($s['social_instagram'] ?? '') ?>" placeholder="https://www.instagram.com/..."></div>
        <div class="fg"><label class="fl">▶️ YouTube URL</label><input type="url" name="social_youtube" class="fi" value="<?= htmlspecialchars($s['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/@..."></div>
        <div class="fg"><label class="fl">🎵 TikTok URL</label><input type="url" name="social_tiktok" class="fi" value="<?= htmlspecialchars($s['social_tiktok'] ?? '') ?>" placeholder="https://www.tiktok.com/@..."></div>
        <div class="fg"><label class="fl">💬 WhatsApp URL</label><input type="url" name="social_whatsapp" class="fi" value="<?= htmlspecialchars($s['social_whatsapp'] ?? '') ?>" placeholder="https://wa.me/62..."></div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header"><div class="admin-card-title">🦶 Footer</div></div>
    <div style="padding:1.5rem">
      <div class="fg"><label class="fl">Tagline Footer</label><textarea name="footer_tagline" class="ft" rows="2"><?= htmlspecialchars($s['footer_tagline'] ?? '') ?></textarea></div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary" style="font-size:15px;padding:.75rem 2rem">💾 Simpan Semua Pengaturan</button>
</form>
</div></div></div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body></html>
