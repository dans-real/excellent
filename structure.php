<?php
$pageTitle = 'Struktur Organisasi';
require_once __DIR__ . '/includes/header.php';
$pdo = getDB();
$leader    = $pdo->query("SELECT * FROM members WHERE level='leader' AND is_active=1 ORDER BY sort_order LIMIT 1")->fetch();
$core      = $pdo->query("SELECT * FROM members WHERE level='core' AND is_active=1 ORDER BY sort_order")->fetchAll();
$ministers = $pdo->query("SELECT * FROM members WHERE level IN ('minister','staff') AND is_active=1 ORDER BY ministry, sort_order")->fetchAll();
$ministries = ['Ministry of Education','Ministry of Entrepreneurship','Ministry of Public Relation','Ministry of Human Resource'];
$ministryIcons = ['Ministry of Education'=>'📚','Ministry of Entrepreneurship'=>'💼','Ministry of Public Relation'=>'📢','Ministry of Human Resource'=>'🌱'];
$ministryBg = ['Ministry of Education'=>'rgba(30,58,95,0.08)','Ministry of Entrepreneurship'=>'rgba(245,166,35,0.12)','Ministry of Public Relation'=>'rgba(15,110,86,0.1)','Ministry of Human Resource'=>'rgba(153,53,86,0.1)'];
$ministrySubtitle = ['Ministry of Education'=>'Pengembangan & Pelatihan','Ministry of Entrepreneurship'=>'Kewirausahaan & Ekonomi','Ministry of Public Relation'=>'Humas & Komunikasi','Ministry of Human Resource'=>'SDM & Pengembangan'];
?>
<div class="page-hero">
  <div class="section-label" style="margin:0 auto 1rem">✦ Kepengurusan</div>
  <h1>Struktur Organisasi<br><em style="color:var(--gold)">Government 2024</em></h1>
  <p>Digerakkan mahasiswa berdedikasi yang berkomitmen membawa EXCELLENT ke level berikutnya.</p>
</div>

<section class="bg-surface">
  <div class="container">
    <?php if($leader): ?>
    <div class="org-level-label">Pimpinan Utama</div>
    <div class="org-level">
      <div class="org-card leader reveal">
        <div class="org-avatar-sm" style="background:<?= h($leader['color']) ?>">
          <?php if(!empty($leader['photo'])): ?><img src="<?= UPLOAD_URL . h($leader['photo']) ?>" alt="<?= h($leader['name']) ?>"><?php else: ?><?= h($leader['initials']) ?><?php endif; ?>
        </div>
        <div class="org-name"><?= h($leader['name']) ?></div>
        <div class="org-role"><?= h($leader['role']) ?></div>
      </div>
    </div>
    <div class="org-connector"></div>
    <?php endif; ?>

    <?php if(!empty($core)): ?>
    <div class="org-level-label" style="margin-top:1.5rem">Sekretariat & Pimpinan</div>
    <div class="org-level">
      <?php foreach($core as $i=>$m): ?>
      <div class="org-card reveal <?= $i>0?'d'.$i:'' ?>">
        <div class="org-avatar-sm" style="background:<?= h($m['color']) ?>">
          <?php if(!empty($m['photo'])): ?><img src="<?= UPLOAD_URL . h($m['photo']) ?>" alt="<?= h($m['name']) ?>"><?php else: ?><?= h($m['initials']) ?><?php endif; ?>
        </div>
        <div class="org-name"><?= h($m['name']) ?></div>
        <div class="org-role"><?= h($m['role']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div style="margin-top:4rem">
      <div class="section-label">✦ Kementerian</div>
      <h2 class="section-title">Struktur Kementerian</h2>
      <div class="ministry-grid">
        <?php foreach($ministries as $min): ?>
        <?php $mems = array_filter($ministers, fn($m) => $m['ministry'] === $min); ?>
        <div class="min-card reveal">
          <div class="min-header">
            <div class="min-icon" style="background:<?= $ministryBg[$min] ?>"><?= $ministryIcons[$min] ?></div>
            <div><div class="min-title"><?= $min ?></div><div class="min-sub"><?= $ministrySubtitle[$min] ?></div></div>
          </div>
          <div class="min-members">
            <?php foreach($mems as $m): ?>
            <div class="min-member">
              <div class="min-av" style="background:<?= h($m['color']) ?>">
                <?php if(!empty($m['photo'])): ?><img src="<?= UPLOAD_URL . h($m['photo']) ?>" alt=""><?php else: ?><?= h($m['initials']) ?><?php endif; ?>
              </div>
              <div><div class="min-mname"><?= h($m['name']) ?></div><div class="min-mrole"><?= h($m['role']) ?></div></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
