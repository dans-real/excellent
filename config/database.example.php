<?php
// Salin file ini menjadi database.php dan sesuaikan nilainya
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_excellent');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('BASE_URL', 'http://localhost/excellent');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
// Salin & implementasikan fungsi getDB(), getSetting(), getAllSettings() dari database.php asli
