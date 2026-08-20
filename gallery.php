<?php
$pageTitle = 'Galeri Kegiatan';
require_once __DIR__ . '/includes/header.php';
$pdo = getDB();
$cat = $_GET['cat'] ?? 'all';
$where = $cat !== 'all' ? "WHERE g.category = " . $pdo->quote($cat) : '';
$galleries = $pdo->query("SELECT g.*, e.title as event_title FROM gallery g LEFT JOIN events e ON g.event_id=e.id $where ORDER BY g.sort_order, g.id DESC")->fetchAll();
$cats = $pdo->query("SELECT DISTINCT category FROM gallery ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
?>
<div class="page-hero">
  <div class="section-label" style="margin:0 auto 1rem">✦ Dokumentasi</div>
  <h1>Galeri Kegiatan<br><em style="color:var(--gold)">EXCELLENT</em></h1>
  <p>Momen-momen berharga dari berbagai kegiatan dan event kami.</p>
</div>
<section class="bg-surface">
  <div class="container">
    <div class="filter-bar">
      <a href="?cat=all" class="filter-btn <?= $cat==='all'?'active':'' ?>">Semua</a>
      <?php foreach($cats as $c): ?><a href="?cat=<?= urlencode($c) ?>" class="filter-btn <?= $cat===$c?'active':'' ?>"><?= h($c) ?></a><?php endforeach; ?>
    </div>
    <?php if(empty($galleries)): ?>
    <div style="text-align:center;padding:4rem;color:var(--text3)"><div style="font-size:3rem;margin-bottom:1rem">📷</div><p>Belum ada foto di galeri.</p></div>
    <?php else: ?>
    <div class="gallery-masonry">
      <?php foreach($galleries as $g):
        $heights = [200, 240, 280, 220, 260, 300];
        $h2 = $heights[$g['id'] % count($heights)];
      ?>
      <div class="gallery-item" onclick="openLightboxItem(<?= $g['id'] ?>)" data-id="<?= $g['id'] ?>">
        <div class="gi-inner" style="height:<?= $h2 ?>px">
          <img src="<?= UPLOAD_URL . h($g['image_path']) ?>" alt="<?= h($g['title']) ?>" style="width:100%;height:100%;object-fit:cover" loading="lazy">
        </div>
        <div class="gi-overlay"><span class="gi-label"><?= h($g['title']) ?></span></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="if(event.target===this)closeLightbox()">
  <button class="lb-close" onclick="closeLightbox()">✕</button>
  <div class="lb-inner">
    <div class="lb-img" id="lb-img"></div>
    <div class="lb-info">
      <div class="lb-title" id="lb-title"></div>
      <div class="lb-desc" id="lb-desc"></div>
    </div>
  </div>
</div>

<script>
const galleryData = <?= json_encode(array_map(fn($g) => [
  'id' => $g['id'],
  'title' => $g['title'],
  'desc' => $g['description'] ?? '',
  'img' => UPLOAD_URL . $g['image_path'],
], $galleries)) ?>;

function openLightboxItem(id) {
  const item = galleryData.find(g => g.id == id);
  if (!item) return;
  document.getElementById('lb-title').textContent = item.title;
  document.getElementById('lb-desc').textContent = item.desc;
  document.getElementById('lb-img').innerHTML = `<img src="${item.img}" alt="${item.title}" style="width:100%;height:100%;object-fit:cover">`;
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if(e.key==='Escape') closeLightbox(); });
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
