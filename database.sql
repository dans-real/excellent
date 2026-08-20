-- ============================================================
-- UKM-F EXCELLENT — Database Schema
-- Copy paste seluruh isi file ini ke phpMyAdmin > SQL tab
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_excellent` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_excellent`;

-- ============================================================
-- TABLE: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL COMMENT 'bcrypt hash',
  `email` VARCHAR(100),
  `avatar` VARCHAR(255) DEFAULT NULL,
  `role` ENUM('superadmin','admin') DEFAULT 'admin',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: site_settings (konten beranda)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key_name` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: events
-- ============================================================
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `category` ENUM('competition','seminar','workshop','internal') NOT NULL,
  `status` ENUM('open','upcoming','closed') DEFAULT 'upcoming',
  `event_date` VARCHAR(100),
  `location` VARCHAR(200),
  `description` TEXT,
  `lead` TEXT COMMENT 'Teks intro di halaman detail',
  `prize` VARCHAR(100) DEFAULT NULL,
  `fee` VARCHAR(100) DEFAULT NULL,
  `capacity` VARCHAR(100) DEFAULT NULL,
  `emoji` VARCHAR(10) DEFAULT '📅',
  `gradient_from` VARCHAR(20) DEFAULT '#1E3A5F',
  `gradient_to` VARCHAR(20) DEFAULT '#2a5f9e',
  `branches` TEXT COMMENT 'JSON array cabang lomba',
  `timeline` TEXT COMMENT 'JSON array timeline',
  `is_featured` TINYINT(1) DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: members
-- ============================================================
CREATE TABLE IF NOT EXISTS `members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `initials` VARCHAR(5) NOT NULL,
  `role` VARCHAR(100) NOT NULL,
  `ministry` VARCHAR(100) NOT NULL,
  `level` ENUM('leader','core','minister','staff') DEFAULT 'staff',
  `color` VARCHAR(20) DEFAULT '#1E3A5F',
  `photo` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `instagram` VARCHAR(100) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: gallery
-- ============================================================
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `image_path` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) DEFAULT 'Umum',
  `event_id` INT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: messages (form kontak)
-- ============================================================
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `university` VARCHAR(100),
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200),
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- DEFAULT DATA: admin
-- password: admin123 (bcrypt)
-- ============================================================
INSERT INTO `admins` (`name`, `username`, `password`, `email`, `role`) VALUES
('Super Admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@ukmfexcellent.com', 'superadmin');

-- ============================================================
-- DEFAULT DATA: site_settings
-- ============================================================
INSERT INTO `site_settings` (`key_name`, `value`) VALUES
('hero_title', 'From Zero to Hero, Together.'),
('hero_subtitle', 'Unit kegiatan mahasiswa terdepan dalam pengembangan kemampuan Bahasa Inggris. Bergabunglah dengan ratusan mahasiswa yang telah meraih prestasi bersama kami.'),
('hero_badge', 'UKM-F EXCELLENT — FIP'),
('stat_alumni', '500'),
('stat_events', '12'),
('stat_tahun', '2015'),
('about_title', 'Organisasi Mahasiswa Berbasis Bahasa Inggris'),
('about_desc', 'UKM-F EXCELLENT berfokus pada pengembangan Bahasa Inggris, jiwa kepemimpinan, dan kewirausahaan mahasiswa FIP.'),
('visi', 'Menjadi unit kegiatan mahasiswa yang unggul, inovatif, dan berdaya saing tinggi dalam mengembangkan kemampuan Bahasa Inggris serta mencetak generasi pemimpin yang berkarakter dan berprestasi di tingkat nasional maupun internasional.'),
('misi', 'Menyelenggarakan program pengembangan Bahasa Inggris yang berkualitas\nMemfasilitasi mahasiswa dalam kompetisi nasional dan internasional\nMembangun budaya belajar yang aktif, kolaboratif, dan inovatif\nMengembangkan jiwa kepemimpinan dan kewirausahaan anggota\nMenjalin kerjasama dengan berbagai instansi pendidikan'),
('contact_address', 'Gedung Rektorat lt.2, FIP UNESA Surabaya'),
('contact_whatsapp', '+62 895-803-316-833'),
('contact_email', 'ukmfexcellent@gmail.com'),
('contact_website', 'ukmfexcellent.wordpress.com'),
('social_instagram', 'https://www.instagram.com/ukmf.excellent'),
('social_youtube', 'https://youtube.com/@UKMFIPEXCELLENT'),
('social_tiktok', 'https://www.tiktok.com/@ukmfexcellent'),
('social_whatsapp', 'https://wa.me/6289580331683'),
('footer_tagline', 'Unit kegiatan mahasiswa FIP yang berfokus pada pengembangan Bahasa Inggris dan kepemimpinan. From Zero to Hero.'),
('year_founded', '2015'),
('site_name', 'UKM-F EXCELLENT');

-- ============================================================
-- DEFAULT DATA: events
-- ============================================================
INSERT INTO `events` (`title`,`slug`,`category`,`status`,`event_date`,`location`,`description`,`lead`,`prize`,`emoji`,`gradient_from`,`gradient_to`,`is_featured`,`sort_order`) VALUES
('Galaxy Competition (GACO) 2025','gaco-2025','competition','open','Juni 2025','Online & Offline · Nasional','Kompetisi bergengsi: English Debate, Speech, dan Story Telling untuk mahasiswa se-Indonesia.','Kompetisi bergengsi tahunan yang mempertemukan mahasiswa terbaik se-Indonesia dalam ajang adu kemampuan Bahasa Inggris.','Rp 10.000.000','🏆','#1E3A5F','#2a5f9e',1,1),
('English Week 2025','english-week-2025','workshop','upcoming','Agustus 2025','Kampus FIP · Internal','Sepekan program intensif Bahasa Inggris: debate, discussion, dan challenge.','Sepekan penuh program intensif yang dirancang untuk mempercepat perkembangan skill komunikasi anggota.','','🗣️','#d4880a','#f5a623',1,2),
('Seminar TOEFL 2025','seminar-toefl-2025','seminar','open','September 2025','Hybrid (Online & Offline)','Training TOEFL bersama expert tutor dari Kampung Inggris.','Tingkatkan skor TOEFL kamu bersama expert tutor dalam seminar satu hari yang komprehensif.','','📝','#0f6e56','#1d9e75',0,3),
('CASE Competition 4.0','case-competition-4','competition','upcoming','Oktober 2025','Online','Character Design + English Description untuk mahasiswa seluruh Indonesia.','Character Design Description Competition yang memadukan kreativitas visual dengan narasi berbahasa Inggris.','Rp 5.000.000','🎨','#534AB7','#7F77DD',0,4),
('EC3 — English Club Camp','ec3-2025','internal','upcoming','November 2025','Outcamp Area · Multi-Kampus','Camp kolaborasi 3 hari antar English Club se-Jawa Timur.','Camp kolaborasi 3 hari antar English Club untuk membangun jejaring dan skill bersama.','','🏕️','#993556','#D4537E',0,5),
('Seminar Beasiswa LPDP','seminar-lpdp-2025','seminar','upcoming','Desember 2025','Online (Zoom)','Panduan sukses mendaftar LPDP bersama alumni penerima beasiswa.','Panduan lengkap strategi sukses mendaftar beasiswa LPDP bersama alumni yang telah terbukti berhasil.','','🎓','#854F0B','#EF9F27',0,6);

-- ============================================================
-- DEFAULT DATA: members
-- ============================================================
INSERT INTO `members` (`name`,`initials`,`role`,`ministry`,`level`,`color`,`sort_order`) VALUES
('Ahmad Rizqullah','AR','Ketua Umum','General Government','leader','#1E3A5F',1),
('Bagas Hermawan','BH','Wakil Ketua','General Government','core','#d4880a',2),
('Fatimah Nuraini','FN','Sekretaris Jenderal','General Government','core','#0f6e56',3),
('Lira Amalia','LA','Bendahara Umum','General Government','core','#993556',4),
('Dimas Pratama','DP','Menteri Pendidikan','Ministry of Education','minister','#1E3A5F',5),
('Nadia Ramadhani','NR','Wakil Menteri','Ministry of Education','staff','#2a5f9e',6),
('Amalia Fadya','AF','Staff Ahli','Ministry of Education','staff','#4a7abf',7),
('Siti Rahmawati','SR','Menteri Kewirausahaan','Ministry of Entrepreneurship','minister','#d4880a',8),
('Rizky Kurniawan','RK','Wakil Menteri','Ministry of Entrepreneurship','staff','#c07d1a',9),
('Ika Hendrawati','IH','Staff Program','Ministry of Entrepreneurship','staff','#d4930e',10),
('Yoga Prasetyo','YP','Menteri Humas','Ministry of Public Relation','minister','#0f6e56',11),
('Maya Mutiara','MM','Wakil Menteri','Ministry of Public Relation','staff','#1d8a6b',12),
('Farhan Setiawan','FS','Staff Media','Ministry of Public Relation','staff','#2aaa85',13),
('Lisa Wulandari','LW','Menteri SDM','Ministry of Human Resource','minister','#993556',14),
('Aldi Nugroho','AN','Wakil Menteri','Ministry of Human Resource','staff','#b84268',15),
('Zahra Amira','ZA','Staff Rekrutmen','Ministry of Human Resource','staff','#d35a7e',16);

SELECT 'Database berhasil dibuat!' AS status;

-- ============================================================
-- FIX PASSWORD ADMIN (jalankan ini jika login gagal)
-- Hash di bawah = admin123 dengan PHP password_hash bcrypt
-- ============================================================
-- Jalankan query ini di phpMyAdmin jika login admin gagal:
/*
UPDATE `admins`
SET `password` = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B9bd/C2'
WHERE `username` = 'admin';
*/

-- ATAU — buat file generate_hash.php di folder excellent/:
-- <?php echo password_hash('admin123', PASSWORD_BCRYPT); ?>
-- Buka di browser, copy hasilnya, paste ke tabel admins kolom password
-- HAPUS file tersebut setelah selesai!
