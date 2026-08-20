<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
session_destroy();
header('Location: ' . BASE_URL . '/admin/login.php');
exit;
