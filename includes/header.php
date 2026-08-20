<?php
// Public site header
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/functions.php';
$s = getAllSettings();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle ?? ($s['site_name'] ?? 'UKM-F EXCELLENT')) ?> — <?= h($s['site_name'] ?? 'UKM-F EXCELLENT') ?></title>
<meta name="description" content="<?= h($s['hero_subtitle'] ?? '') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<nav id="navbar">
  <div class="nav-inner">
    <a class="nav-logo" href="<?= BASE_URL ?>">
      <div class="nav-logo-mark">EX</div>
      <div class="nav-logo-text"><?= h($s['site_name'] ?? 'UKM-F EXCELLENT') ?><span>From Zero to Hero</span></div>
    </a>
    <ul class="nav-links">
      <li><a href="<?= BASE_URL ?>" class="<?= $currentPage==='index'?'active':'' ?>">Home</a></li>
      <li><a href="<?= BASE_URL ?>/about.php" class="<?= $currentPage==='about'?'active':'' ?>">About</a></li>
      <li><a href="<?= BASE_URL ?>/structure.php" class="<?= $currentPage==='structure'?'active':'' ?>">Structure</a></li>
      <li><a href="<?= BASE_URL ?>/events.php" class="<?= $currentPage==='events'?'active':'' ?>">Events</a></li>
      <li><a href="<?= BASE_URL ?>/gallery.php" class="<?= $currentPage==='gallery'?'active':'' ?>">Gallery</a></li>
      <li><a href="<?= BASE_URL ?>/contact.php" class="nav-cta <?= $currentPage==='contact'?'active':'' ?>">Contact</a></li>
    </ul>
    <div class="nav-right">
      <button class="theme-toggle" id="themeBtn" title="Toggle dark mode">🌙</button>
      <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
    </div>
  </div>
</nav>
<div class="mobile-menu" id="mobileMenu">
  <button class="theme-toggle-mobile" onclick="toggleTheme()">🌙 &nbsp;Dark Mode</button>
  <a href="<?= BASE_URL ?>">🏠 &nbsp;Home</a>
  <a href="<?= BASE_URL ?>/about.php">ℹ️ &nbsp;About</a>
  <a href="<?= BASE_URL ?>/structure.php">🏛️ &nbsp;Structure</a>
  <a href="<?= BASE_URL ?>/events.php">📅 &nbsp;Events</a>
  <a href="<?= BASE_URL ?>/gallery.php">📷 &nbsp;Gallery</a>
  <a href="<?= BASE_URL ?>/contact.php">✉️ &nbsp;Contact</a>
</div>
