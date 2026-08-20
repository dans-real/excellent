<?php
// ⚠️  FILE INI HANYA UNTUK SETUP — HAPUS SETELAH DIPAKAI!
// Buka: http://localhost/excellent/generate_hash.php
// Copy hash yang muncul → paste ke tabel admins di phpMyAdmin

$password = 'admin123'; // ganti password yang kamu inginkan
$hash = password_hash($password, PASSWORD_BCRYPT);

// Verifikasi hash valid
$valid = password_verify($password, $hash);
?>
<!DOCTYPE html>
<html>
<head><title>Generate Hash</title>
<style>body{font-family:monospace;padding:2rem;background:#f8fafc}
.box{background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1.5rem;max-width:700px}
.hash{background:#1e293b;color:#4ade80;padding:1rem;border-radius:6px;word-break:break-all;font-size:14px;margin:1rem 0}
.warn{background:#fef3c7;border:1px solid #fcd34d;padding:.75rem 1rem;border-radius:6px;color:#92400e;margin-top:1rem;font-size:13px}
button{background:#1E3A5F;color:#fff;border:none;padding:.5rem 1rem;border-radius:6px;cursor:pointer;font-size:13px}
</style>
</head>
<body>
<div class="box">
  <h2>🔐 Password Hash Generator</h2>
  <p>Password: <strong><?= htmlspecialchars($password) ?></strong></p>
  <p>Status: <?= $valid ? '✅ Hash valid' : '❌ Hash tidak valid' ?></p>
  <p><strong>Hash (copy ini ke tabel admins):</strong></p>
  <div class="hash" id="hash"><?= htmlspecialchars($hash) ?></div>
  <button onclick="navigator.clipboard.writeText('<?= addslashes($hash) ?>').then(()=>alert('✅ Hash disalin!'))">📋 Copy Hash</button>
  <br><br>
  <p><strong>Query SQL untuk phpMyAdmin:</strong></p>
  <div class="hash">UPDATE `admins` SET `password` = '<?= htmlspecialchars($hash) ?>' WHERE `username` = 'admin';</div>
  <div class="warn">
    ⚠️ <strong>PENTING:</strong> Hapus file <code>generate_hash.php</code> ini setelah selesai!<br>
    File ini tidak boleh ada di server production.
  </div>
</div>
</body>
</html>
