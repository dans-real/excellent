<?php
$pageTitle = 'Events & Program';
require_once __DIR__ . '/includes/header.php';
$pdo = getDB();

$cat = $_GET['cat'] ?? 'all';
$q   = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 9;

$where = ['1=1'];
$params = [];
if ($cat !== 'all') { $where[] = 'category = ?'; $params[] = $cat; }
if ($q !== '') { $where[] = '(title LIKE ? OR description LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
$whereSQL = implode(' AND ', $where);

$total = $pdo->prepare("SELECT COUNT(*) FROM events WHERE $whereSQL");
$total->execute($params);
$totalCount = $total->fetchColumn();
$pg = paginate($totalCount, $perPage, $page);

$stmt = $pdo->prepare("SELECT * FROM events WHERE $whereSQL ORDER BY sort_order, id DESC LIMIT $perPage OFFSET {$pg['offset']}");
$stmt->execute($params);
$events = $stmt->fetchAll();
?>
<div class="page-hero">
  <div class="section-label" style="margin:0 auto 1rem">✦ Events & Program</div>
  <h1>Semua Kegiatan<br><em style="color:var(--gold)">EXCELLENT</em></h1>
  <p>Temukan event, kompetisi, dan program pengembangan diri yang kami selenggarakan.</p>
</div>

<section class="bg-surface">
  <div class="container">
    <form method="GET" style="margin-bottom:1.5rem">
      <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" name="q" class="search-input" placeholder="Cari event..." value="<?= h($q) ?>">
      </div>
    </form>
    <div class="filter-bar">
      <?php foreach(['all'=>'Semua','competition'=>'Competition','seminar'=>'Seminar','workshop'=>'Workshop','internal'=>'Internal'] as $k=>$v): ?>
      <a href="?cat=<?= $k ?><?= $q ? '&q='.urlencode($q) : '' ?>" class="filter-btn <?= $cat===$k?'active':'' ?>"><?= $v ?></a>
      <?php endforeach; ?>
    </div>

    <?php if(empty($events)): ?>
    <div style="text-align:center;padding:3rem;color:var(--text3)"><div style="font-size:3rem;margin-bottom:1rem">🔍</div><p>Tidak ada event yang ditemukan.</p></div>
    <?php else: ?>
    <div class="events-grid">
      <?php foreach($events as $i => $ev): ?>
      <a href="<?= BASE_URL ?>/event-detail.php?slug=<?= urlencode($ev['slug']) ?>" class="event-card reveal <?= $i>0&&$i%3>0?'d'.($i%3):'' ?>">
        <div class="ec-img" style="background:linear-gradient(135deg,<?= h($ev['gradient_from']) ?>,<?= h($ev['gradient_to']) ?>)"><?= h($ev['emoji']) ?></div>
        <div class="ec-body">
          <div class="ec-meta">
            <span class="badge badge-gold"><?= ucfirst(h($ev['category'])) ?></span>
            <?php if($ev['status']==='open'): ?><span class="badge badge-green">Open</span>
            <?php elseif($ev['status']==='closed'): ?><span class="badge" style="background:rgba(220,38,38,.1);color:#dc2626">Closed</span><?php endif; ?>
          </div>
          <div class="ec-title"><?= h($ev['title']) ?></div>
          <div class="ec-date">📅 <?= h($ev['event_date']) ?> · <?= h($ev['location']) ?></div>
          <div class="ec-desc"><?= h($ev['description']) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <!-- PAGINATION -->
    <?php if($pg['total_pages'] > 1): ?>
    <div style="display:flex;justify-content:center;gap:.5rem;margin-top:2rem">
      <?php if($pg['has_prev']): ?><a href="?cat=<?= $cat ?>&q=<?= urlencode($q) ?>&page=<?= $page-1 ?>" class="btn btn-ghost btn-sm">← Prev</a><?php endif; ?>
      <?php for($i=1;$i<=$pg['total_pages'];$i++): ?>
      <a href="?cat=<?= $cat ?>&q=<?= urlencode($q) ?>&page=<?= $i ?>" class="btn btn-sm <?= $i===$page?'btn-navy':'btn-ghost' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if($pg['has_next']): ?><a href="?cat=<?= $cat ?>&q=<?= urlencode($q) ?>&page=<?= $page+1 ?>" class="btn btn-ghost btn-sm">Next →</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
