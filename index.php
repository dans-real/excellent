<?php
$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/header.php';

$pdo = getDB();
$featuredEvents = $pdo->query("SELECT * FROM events WHERE is_featured=1 ORDER BY sort_order LIMIT 3")->fetchAll();
$allEvents = $pdo->query("SELECT * FROM events ORDER BY sort_order LIMIT 3")->fetchAll();
$displayEvents = !empty($featuredEvents) ? $featuredEvents : $allEvents;
$coreMembers = $pdo->query("SELECT * FROM members WHERE level IN ('leader','core','minister') AND is_active=1 ORDER BY sort_order LIMIT 4")->fetchAll();
?>

<!-- HERO -->
<section class="hero">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="hero-orb hero-orb-3"></div>
  <div class="hero-inner">
    <div>
      <div class="hero-eyebrow">✦ <?= h($s['hero_badge'] ?? 'UKM-F EXCELLENT — FIP') ?></div>
      <h1 class="hero-title"><?php
        $parts = explode(',', $s['hero_title'] ?? 'From Zero to Hero, Together.');
        $first = trim($parts[0]);
        $first = preg_replace('/Zero/', '<em>Zero</em>', $first);
        echo $first;
        if (count($parts) > 1) echo ',<br>' . implode(',', array_slice($parts, 1));
      ?></h1>
      <p class="hero-desc"><?= h($s['hero_subtitle'] ?? '') ?></p>
      <div class="hero-actions">
        <a href="<?= BASE_URL ?>/events.php" class="btn btn-primary">Lihat Events ↗</a>
        <a href="<?= BASE_URL ?>/about.php" class="btn btn-outline-white">Tentang Kami</a>
      </div>
      <div class="hero-stats">
        <div>
          <div class="hero-stat-num" id="stat1" data-target="<?= h($s['stat_alumni'] ?? '500') ?>">0+</div>
          <div class="hero-stat-label">Alumni Aktif</div>
        </div>
        <div>
          <div class="hero-stat-num" id="stat2" data-target="<?= h($s['stat_events'] ?? '12') ?>">0+</div>
          <div class="hero-stat-label">Event/Tahun</div>
        </div>
        <div>
          <div class="hero-stat-num" id="stat3" data-target="<?= h($s['stat_tahun'] ?? '2015') ?>">0</div>
          <div class="hero-stat-label">Berdiri Sejak</div>
        </div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="hero-card-stack">
        <?php foreach (array_slice($displayEvents, 0, 2) as $i => $ev): ?>
        <div class="hero-card">
          <div class="hero-card-img" style="background:linear-gradient(135deg,<?= h($ev['gradient_from']) ?>,<?= h($ev['gradient_to']) ?>)"><?= h($ev['emoji']) ?></div>
          <span class="hcard-tag"><?= $ev['status']==='open' ? 'Open Registration' : 'Upcoming Event' ?></span>
          <h3><?= h($ev['title']) ?></h3>
          <p><?= h($ev['event_date']) ?> · <?= h($ev['location']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT SNIPPET -->
<section class="bg-surface">
  <div class="container">
    <div class="about-grid">
      <div class="about-visual-wrap reveal">
        <div class="about-img-box"><span style="font-size:6rem;position:relative;z-index:1">🎓</span></div>
        <div class="about-badge">
          <div class="about-badge-num"><?= date('Y') - intval($s['year_founded'] ?? 2015) ?>+</div>
          <div class="about-badge-text">Tahun Berdiri</div>
        </div>
      </div>
      <div class="reveal d1">
        <div class="section-label">✦ Tentang Kami</div>
        <h2 class="display-lg" style="color:var(--text);margin-bottom:1rem"><?= h($s['about_title'] ?? '') ?></h2>
        <p style="font-size:1.05rem;color:var(--text-2);line-height:1.7;max-width:560px"><?= h($s['about_desc'] ?? '') ?></p>
        <ul class="about-list">
          <li><div class="abl-icon">📚</div><div><div class="abl-title">English Development</div><div class="abl-desc">Program intensif meningkatkan speaking, writing, dan TOEFL score anggota.</div></div></li>
          <li><div class="abl-icon">🌟</div><div><div class="abl-title">Competition & Achievement</div><div class="abl-desc">Fasilitasi mahasiswa berkompetisi di tingkat nasional maupun internasional.</div></div></li>
          <li><div class="abl-icon">🤝</div><div><div class="abl-title">Community & Network</div><div class="abl-desc">Membangun komunitas solid dan jaringan alumni kuat di seluruh Indonesia.</div></div></li>
        </ul>
        <a href="<?= BASE_URL ?>/about.php" class="btn btn-navy" style="margin-top:1.75rem">Selengkapnya →</a>
      </div>
    </div>
  </div>
</section>

<!-- EVENTS PREVIEW -->
<section class="bg-subtle">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem" class="reveal">
      <div><div class="section-label">✦ Events & Program</div><h2 class="display-lg" style="color:var(--text)">Kegiatan Unggulan</h2></div>
      <a href="<?= BASE_URL ?>/events.php" class="btn btn-ghost">Lihat Semua →</a>
    </div>
    <div class="events-grid">
      <?php foreach ($displayEvents as $i => $ev): ?>
      <a href="<?= BASE_URL ?>/event-detail.php?slug=<?= urlencode($ev['slug']) ?>" class="event-card reveal <?= $i>0?'d'.$i:'' ?>">
        <div class="ec-img" style="background:linear-gradient(135deg,<?= h($ev['gradient_from']) ?>,<?= h($ev['gradient_to']) ?>)"><?= h($ev['emoji']) ?></div>
        <div class="ec-body">
          <div class="ec-meta">
            <span class="badge badge-gold"><?= ucfirst(h($ev['category'])) ?></span>
            <?php if($ev['status']==='open'): ?><span class="badge badge-green">Open</span><?php endif; ?>
          </div>
          <div class="ec-title"><?= h($ev['title']) ?></div>
          <div class="ec-date">📅 <?= h($ev['event_date']) ?> · <?= h($ev['location']) ?></div>
          <div class="ec-desc"><?= h($ev['description']) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MEMBERS PREVIEW -->
<section class="bg-surface">
  <div class="container">
    <div style="text-align:center;margin-bottom:1rem" class="reveal">
      <div class="section-label" style="margin:0 auto 1rem">✦ Pengurus 2024</div>
      <h2 class="display-lg" style="color:var(--text)">Jajaran Pengurus</h2>
      <p style="color:var(--text-2);max-width:520px;margin:.75rem auto 0;font-size:1.05rem;line-height:1.7">Dipimpin mahasiswa berdedikasi yang berkomitmen membawa EXCELLENT ke level berikutnya.</p>
    </div>
    <div class="members-grid">
      <?php foreach ($coreMembers as $i => $m): ?>
      <div class="member-card reveal <?= $i>0?'d'.$i:'' ?>">
        <div class="m-avatar" style="background:<?= h($m['color']) ?>">
          <?php if(!empty($m['photo'])): ?>
            <img src="<?= UPLOAD_URL . h($m['photo']) ?>" alt="<?= h($m['name']) ?>">
          <?php else: ?><?= h($m['initials']) ?><?php endif; ?>
        </div>
        <div class="m-name"><?= h($m['name']) ?></div>
        <div class="m-role"><?= h($m['role']) ?></div>
        <div class="m-ministry"><?= h($m['ministry']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.75rem" class="reveal">
      <a href="<?= BASE_URL ?>/structure.php" class="btn btn-navy">Lihat Struktur Lengkap →</a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="section-label" style="margin:0 auto 1.5rem;display:table">✦ Bergabung Bersama Kami</div>
  <h2>Siap Menjadi Bagian<br>dari <em style="font-style:italic;color:var(--gold)">EXCELLENT?</em></h2>
  <p>Daftarkan dirimu dan mulai perjalanan dari Zero to Hero bersama ratusan mahasiswa lainnya.</p>
  <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
    <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary">Hubungi Kami ✉️</a>
    <a href="<?= h($s['social_instagram'] ?? '#') ?>" target="_blank" class="btn btn-outline-white">Follow Instagram 📱</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
