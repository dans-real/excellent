<?php
$pageTitle = 'Tentang Kami';
require_once __DIR__ . '/includes/header.php';
$misiList = array_filter(explode("\n", $s['misi'] ?? ''));
$history = [
  ['year'=>'2015','title'=>'Pendirian UKM-F EXCELLENT','desc'=>'Didirikan sekelompok mahasiswa FIP dengan visi besar. Berawal dari 20 anggota perdana.'],
  ['year'=>'2017','title'=>'CASE Competition Pertama','desc'=>'Kompetisi desain karakter memadukan kreativitas visual dan Bahasa Inggris, diikuti 15 universitas se-Indonesia.'],
  ['year'=>'2019','title'=>'Seminar TOEFL & EC3','desc'=>'Seminar TOEFL pertama bersama Kampung Inggris dan peluncuran EC3 antar universitas.'],
  ['year'=>'2022','title'=>'500 Alumni & CASE 3.0','desc'=>'Milestone 500 alumni aktif dan CASE Competition 3.0 dengan tema "Be Your Own Hero for Others".'],
  ['year'=>'2024','title'=>'Galaxy Competition & Era Digital','desc'=>'Peluncuran Galaxy Competition (GACO) dan penguatan program kewirausahaan digital anggota.'],
];
?>
<div class="page-hero">
  <div class="section-label" style="margin:0 auto 1rem">✦ Tentang Kami</div>
  <h1>Mengenal UKM-F<br><em style="color:var(--gold)">EXCELLENT</em></h1>
  <p>Unit kegiatan mahasiswa yang mendorong pengembangan diri melalui Bahasa Inggris sejak <?= h($s['year_founded'] ?? '2015') ?>.</p>
</div>

<section class="bg-surface">
  <div class="container">
    <div class="section-label">✦ Profil</div>
    <h2 class="section-title">Siapa Kami?</h2>
    <p style="color:var(--text2);line-height:1.8;max-width:800px;margin-top:1rem;font-size:1.05rem">
      UKM-F EXCELLENT adalah organisasi kemahasiswaan di bawah naungan Fakultas Ilmu Pendidikan yang berfokus pada pengembangan kecakapan Bahasa Inggris, kepemimpinan, dan jiwa kewirausahaan. Dengan motto <strong style="color:var(--navy)">"From Zero to Hero"</strong>, kami percaya setiap mahasiswa memiliki potensi untuk berkembang.
    </p>
  </div>
</section>

<section class="bg-subtle">
  <div class="container">
    <div class="section-label">✦ Arah Organisasi</div>
    <h2 class="section-title">Visi & Misi</h2>
    <div class="vm-grid">
      <div class="vm-card reveal"><div style="font-size:2.5rem;margin-bottom:1rem">🔭</div><h3>Visi</h3><p><?= nl2br(h($s['visi'] ?? '')) ?></p></div>
      <div class="vm-card reveal d1"><div style="font-size:2.5rem;margin-bottom:1rem">🎯</div><h3>Misi</h3>
        <ul><?php foreach($misiList as $m): ?><li><?= h(trim($m)) ?></li><?php endforeach; ?></ul>
      </div>
    </div>
  </div>
</section>

<section class="bg-surface">
  <div class="container">
    <div class="section-label">✦ Perjalanan</div>
    <h2 class="section-title">Sejarah EXCELLENT</h2>
    <div class="timeline">
      <?php foreach($history as $h2): ?>
      <div class="tl-item reveal">
        <div class="tl-dot"><?= $h2['year'] ?></div>
        <div class="tl-content"><h4><?= htmlspecialchars($h2['title']) ?></h4><p><?= htmlspecialchars($h2['desc']) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-section">
  <h2>Tertarik Bergabung?</h2>
  <p>Hubungi kami untuk info pendaftaran anggota baru.</p>
  <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary">Hubungi Kami ✉️</a>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
