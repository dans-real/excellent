<?php
require_once __DIR__ . '/includes/header.php';
$pdo = getDB();
$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: ' . BASE_URL . '/events.php'); exit; }
$stmt = $pdo->prepare("SELECT * FROM events WHERE slug = ?");
$stmt->execute([$slug]);
$ev = $stmt->fetch();
if (!$ev) { header('Location: ' . BASE_URL . '/events.php'); exit; }
$pageTitle = $ev['title'];
$branches = json_decode($ev['branches'] ?? '[]', true) ?: [];
$timeline = json_decode($ev['timeline'] ?? '[]', true) ?: [];
?>
<div class="ev-detail-hero">
  <div class="ev-detail-inner">
    <a href="<?= BASE_URL ?>/events.php" class="back-btn">← Kembali ke Events</a>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:.75rem">
      <span class="badge badge-gold"><?= ucfirst(h($ev['category'])) ?></span>
      <?php if($ev['status']==='open'): ?><span class="badge badge-green">Open</span><?php endif; ?>
    </div>
    <h1><?= h($ev['title']) ?></h1>
    <p class="lead"><?= h($ev['lead']) ?></p>
    <div class="ev-meta-bar">
      <div class="ev-meta-item"><strong><?= h($ev['event_date']) ?></strong>Tanggal</div>
      <div class="ev-meta-item"><strong><?= h($ev['location']) ?></strong>Lokasi</div>
      <?php if($ev['prize']): ?><div class="ev-meta-item"><strong><?= h($ev['prize']) ?></strong>Total Hadiah</div><?php endif; ?>
      <?php if($ev['fee']): ?><div class="ev-meta-item"><strong><?= h($ev['fee']) ?></strong>Biaya</div><?php endif; ?>
      <?php if($ev['capacity']): ?><div class="ev-meta-item"><strong><?= h($ev['capacity']) ?></strong>Kapasitas</div><?php endif; ?>
    </div>
  </div>
</div>
<section class="bg-surface">
  <div class="container" style="display:grid;grid-template-columns:1fr 320px;gap:3rem;align-items:start;max-width:1000px">
    <div class="ev-content" style="padding:0">
      <?php if(!empty($branches)): ?>
      <h2>Cabang Lomba / Program</h2>
      <ul><?php foreach($branches as $b): ?><li><?= h($b) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>
      <?php if(!empty($timeline)): ?>
      <h2>Timeline</h2>
      <div style="display:flex;flex-direction:column;gap:.75rem">
        <?php foreach($timeline as $t): ?>
        <div style="display:flex;gap:1rem;align-items:start">
          <span style="font-size:11px;background:rgba(245,166,35,.15);color:var(--gold-dark);padding:4px 12px;border-radius:100px;font-weight:600;white-space:nowrap"><?= h($t['date'] ?? $t[0] ?? '') ?></span>
          <span style="color:var(--text2)"><?= h($t['label'] ?? $t[1] ?? '') ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php if($ev['description']): ?><p style="margin-top:2rem"><?= nl2br(h($ev['description'])) ?></p><?php endif; ?>
    </div>
    <div class="ev-sidebar">
      <div style="aspect-ratio:1;border-radius:var(--r-md);margin-bottom:1rem;display:flex;align-items:center;justify-content:center;font-size:5rem;background:linear-gradient(135deg,<?= h($ev['gradient_from']) ?>,<?= h($ev['gradient_to']) ?>)"><?= h($ev['emoji']) ?></div>
      <h3 style="font-weight:700;font-size:14px;color:var(--text);margin-bottom:.75rem"><?= h($ev['title']) ?></h3>
      <p style="font-size:12px;color:var(--text3);margin-bottom:.25rem">📅 <?= h($ev['event_date']) ?></p>
      <p style="font-size:12px;color:var(--text3);margin-bottom:.25rem">📍 <?= h($ev['location']) ?></p>
      <?php if($ev['prize']): ?><p style="font-size:12px;color:var(--text3);margin-bottom:.25rem">🏆 <?= h($ev['prize']) ?></p><?php endif; ?>
      <?php if($ev['fee']): ?><p style="font-size:12px;color:var(--text3);margin-bottom:1rem">💳 <?= h($ev['fee']) ?></p><?php endif; ?>
      <a href="<?= h($s['social_instagram'] ?? '#') ?>" target="_blank" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:.5rem">Daftar Sekarang</a>
      <a href="<?= h($s['social_whatsapp'] ?? '#') ?>" target="_blank" class="btn btn-ghost" style="width:100%;justify-content:center;margin-top:.5rem">Tanya via WhatsApp</a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
