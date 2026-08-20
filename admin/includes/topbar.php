<?php $admin = currentAdmin(); ?>
<header class="admin-topbar">
  <div style="display:flex;align-items:center;gap:1rem">
    <button onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none;background:none;border:none;cursor:pointer;font-size:20px" class="hamburger-admin">☰</button>
    <div class="admin-topbar-title"><?= $pageTitle ?? 'Dashboard' ?></div>
  </div>
  <div class="admin-topbar-right">
    <?php $flash = getFlash(); if($flash): ?>
    <div id="flash-toast" class="flash-toast flash-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
    <?php endif; ?>
    <span class="admin-badge">👤 <?= htmlspecialchars($admin['name'] ?? 'Admin') ?></span>
    <span class="admin-badge" style="background:#f0fdf4;color:#166534"><?= htmlspecialchars($admin['role'] ?? 'admin') ?></span>
  </div>
</header>
