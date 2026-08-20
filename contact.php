<?php
$pageTitle = 'Kontak Kami';
require_once __DIR__ . '/includes/header.php';
$pdo = getDB();
$success = false; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = trim($_POST['name'] ?? '');
  $univ    = trim($_POST['university'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');
  if (!$name || !$email || !$message) {
    $error = 'Nama, email, dan pesan wajib diisi.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Format email tidak valid.';
  } else {
    $stmt = $pdo->prepare("INSERT INTO messages (name,university,email,subject,message) VALUES (?,?,?,?,?)");
    $stmt->execute([$name,$univ,$email,$subject,$message]);
    $success = true;
  }
}
?>
<div class="page-hero">
  <div class="section-label" style="margin:0 auto 1rem">✦ Kontak Kami</div>
  <h1>Hubungi<br><em style="color:var(--gold)">EXCELLENT</em></h1>
  <p>Ada pertanyaan atau ingin bergabung? Kami siap membantu.</p>
</div>
<section class="bg-surface">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-info">
        <h3>Informasi Kontak</h3>
        <?php $cItems = [['📍','Alamat','contact_address'],['📱','WhatsApp','contact_whatsapp'],['✉️','Email','contact_email'],['🌐','Website','contact_website']];
        foreach($cItems as [$icon,$label,$key]): if(!empty($s[$key])): ?>
        <div class="ci-item"><div class="ci-icon"><?= $icon ?></div><div><div class="ci-label"><?= $label ?></div><div class="ci-val"><?= h($s[$key]) ?></div></div></div>
        <?php endif; endforeach; ?>
        <div style="margin-top:2rem">
          <div class="ci-label" style="margin-bottom:1rem">Media Sosial</div>
          <div class="social-links">
            <?php if(!empty($s['social_instagram'])): ?><a href="<?= h($s['social_instagram']) ?>" target="_blank" class="social-link">📷</a><?php endif; ?>
            <?php if(!empty($s['social_youtube'])): ?><a href="<?= h($s['social_youtube']) ?>" target="_blank" class="social-link">▶️</a><?php endif; ?>
            <?php if(!empty($s['social_tiktok'])): ?><a href="<?= h($s['social_tiktok']) ?>" target="_blank" class="social-link">🎵</a><?php endif; ?>
            <?php if(!empty($s['social_whatsapp'])): ?><a href="<?= h($s['social_whatsapp']) ?>" target="_blank" class="social-link">💬</a><?php endif; ?>
          </div>
        </div>
      </div>
      <div class="contact-form">
        <h3 style="font-family:var(--font-display);font-size:1.5rem;color:var(--text);margin-bottom:1.5rem">Kirim Pesan</h3>
        <?php if($success): ?><div class="alert alert-success">✅ Pesan berhasil dikirim! Kami akan segera menghubungi kamu.</div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-error">❌ <?= h($error) ?></div><?php endif; ?>
        <form method="POST">
          <div class="form-row">
            <div class="form-group"><label class="form-label">Nama Lengkap *</label><input type="text" name="name" class="form-input" required placeholder="Nama kamu" value="<?= h($_POST['name'] ?? '') ?>"></div>
            <div class="form-group"><label class="form-label">Asal Universitas</label><input type="text" name="university" class="form-input" placeholder="Universitas kamu" value="<?= h($_POST['university'] ?? '') ?>"></div>
          </div>
          <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-input" required placeholder="email@example.com" value="<?= h($_POST['email'] ?? '') ?>"></div>
          <div class="form-group"><label class="form-label">Subjek</label>
            <select name="subject" class="form-input form-select">
              <?php foreach(['Pertanyaan Umum','Pendaftaran Anggota','Kerjasama & Sponsorship','Informasi Event','Lainnya'] as $opt): ?>
              <option <?= ($_POST['subject']??'')===$opt?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Pesan *</label><textarea name="message" class="form-textarea" required placeholder="Tuliskan pesanmu..."><?= h($_POST['message'] ?? '') ?></textarea></div>
          <button type="submit" class="btn btn-navy" style="width:100%;justify-content:center">Kirim Pesan ✉️</button>
        </form>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
