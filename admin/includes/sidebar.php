<?php
$adminPage = basename($_SERVER['PHP_SELF'], '.php');
function isActive(string $page): string {
  global $adminPage;
  return $adminPage === $page ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-mark">EX</div>
    <div class="sidebar-logo-text">EXCELLENT<span>Admin Panel</span></div>
  </div>
  <nav class="sidebar-nav">
    <div class="sidebar-section">Main</div>
    <a href="<?= BASE_URL ?>/admin/" class="sidebar-link <?= isActive('index') ?>"><span class="icon">🏠</span> Dashboard</a>
    <a href="<?= BASE_URL ?>/admin/pages/settings.php" class="sidebar-link <?= isActive('settings') ?>"><span class="icon">⚙️</span> Pengaturan Beranda</a>
    <div class="sidebar-section">Konten</div>
    <a href="<?= BASE_URL ?>/admin/pages/events.php" class="sidebar-link <?= isActive('events') ?>"><span class="icon">📅</span> Events</a>
    <a href="<?= BASE_URL ?>/admin/pages/members.php" class="sidebar-link <?= isActive('members') ?>"><span class="icon">👥</span> Anggota</a>
    <a href="<?= BASE_URL ?>/admin/pages/gallery.php" class="sidebar-link <?= isActive('gallery') ?>"><span class="icon">🖼️</span> Gallery</a>
    <div class="sidebar-section">Lainnya</div>
    <a href="<?= BASE_URL ?>/admin/pages/messages.php" class="sidebar-link <?= isActive('messages') ?>"><span class="icon">✉️</span> Pesan Masuk</a>
    <a href="<?= BASE_URL ?>/admin/pages/admins.php" class="sidebar-link <?= isActive('admins') ?>"><span class="icon">🔑</span> Kelola Admin</a>
  </nav>
  <div class="sidebar-footer">
    <a href="<?= BASE_URL ?>" target="_blank">🌐 Lihat Website</a>
    <a href="<?= BASE_URL ?>/admin/logout.php" style="margin-top:.5rem;color:#f87171">🚪 Logout</a>
  </div>
</aside>
