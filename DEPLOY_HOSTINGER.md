# 🚀 Panduan Deploy UKM-F EXCELLENT ke Hostinger

## Overview
- Hosting: Hostinger Shared Hosting
- Domain: subdomain .hostingersite.com
- Upload: File Manager hPanel
- DB: MySQL via phpMyAdmin Hostinger

---

## LANGKAH 1 — Persiapan File di Lokal

### 1.1 Buat file hash password dulu (sudah kamu lakukan ✅)
Simpan hash hasil `password_hash('admin123', PASSWORD_BCRYPT)` — kamu akan butuh ini nanti.

### 1.2 Edit config/database.php sebelum upload
Buka file `excellent/config/database.php` dan ubah nilainya:

```php
define('DB_HOST', 'localhost');          // tetap localhost di Hostinger
define('DB_NAME', 'u123456789_excellent'); // ganti sesuai nama DB Hostinger
define('DB_USER', 'u123456789_user');     // ganti sesuai user DB Hostinger
define('DB_PASS', 'PasswordDBmu');        // ganti sesuai password DB
define('BASE_URL', 'https://namamu.hostingersite.com'); // ganti domain kamu
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
```

> ⚠️ BASE_URL TANPA trailing slash, dan pakai https://

### 1.3 ZIP ulang folder excellent/
- Windows: klik kanan folder `excellent` → Send to → Compressed (zip)
- Hasilnya: `excellent.zip`

---

## LANGKAH 2 — Buat Database di Hostinger

1. Login ke **hPanel** → `hpanel.hostinger.com`
2. Sidebar kiri → **Databases** → **MySQL Databases**
3. Klik **Create new database**
4. Isi:
   - Database name: `excellent` (akan jadi `u123456789_excellent`)
   - Username: `excellent_user` (akan jadi `u123456789_excellent_user`)
   - Password: buat password kuat, **catat ini!**
5. Klik **Create** → **catat nama DB, user, dan password**

---

## LANGKAH 3 — Import database.sql ke Hostinger

1. Masih di **Databases** → klik **phpMyAdmin** (di sebelah database yang baru dibuat)
2. phpMyAdmin Hostinger terbuka
3. Klik tab **SQL** di bagian atas
4. **Copy paste** seluruh isi file `database.sql`
5. Klik **Go** → tunggu hingga selesai
6. Setelah berhasil, jalankan query fix password:

```sql
UPDATE `admins` 
SET `password` = 'HASH_DARI_LANGKAH_1_1' 
WHERE `username` = 'admin';
```
> Ganti `HASH_DARI_LANGKAH_1_1` dengan hasil hash yang kamu buat tadi

---

## LANGKAH 4 — Upload File ke Hostinger

1. hPanel → **Files** → **File Manager**
2. Navigasi ke folder `public_html/`
3. Klik tombol **Upload** (pojok atas)
4. Upload file `excellent.zip`
5. Setelah upload selesai, **klik kanan** `excellent.zip` → **Extract**
6. Extract ke `/public_html/` (bukan subfolder)
7. Sekarang isi `public_html/` harusnya:
   ```
   public_html/
   ├── index.php
   ├── about.php
   ├── admin/
   ├── assets/
   ├── config/
   ├── includes/
   ├── uploads/
   └── ...
   ```

> ℹ️ Kalau nama subdomain kamu `ukmfexcellent.hostingersite.com`,
> maka semua file harus ada di `public_html/` langsung (bukan di subfolder).

---

## LANGKAH 5 — Set Permission Folder uploads/

Wajib dilakukan agar upload foto bisa berjalan!

1. Di File Manager, klik kanan folder `uploads/`
2. Pilih **Change Permissions** (atau chmod)
3. Set ke **755** → OK
4. Ulangi untuk subfolder:
   - `uploads/gallery/` → **755**
   - `uploads/members/` → **755**
   - `uploads/hero/` → **755**

---

## LANGKAH 6 — Edit config/database.php di Server

Jika belum diedit sebelum upload:

1. File Manager → buka `public_html/config/database.php`
2. Klik **Edit** (ikon pensil)
3. Ubah nilai DB_HOST, DB_NAME, DB_USER, DB_PASS, BASE_URL
4. Klik **Save**

---

## LANGKAH 7 — Test Website

Buka browser:
- **Website publik:** `https://namamu.hostingersite.com/`
- **Admin panel:** `https://namamu.hostingersite.com/admin/`
  - Username: `admin`
  - Password: `admin123`

---

## 🔧 Troubleshooting

### Error: "Database connection failed"
- Cek ulang DB_NAME, DB_USER, DB_PASS di config/database.php
- Pastikan DB sudah dibuat di hPanel

### Error: "404 Not Found" di semua halaman
- Pastikan file ada di `public_html/` langsung, bukan di subfolder `public_html/excellent/`
- Cek BASE_URL sudah benar tanpa trailing slash

### Error: "500 Internal Server Error"
- File Manager → buka `.htaccess` → pastikan tidak ada syntax error
- Coba rename `.htaccess` jadi `htaccess_backup` sementara untuk test

### Foto tidak bisa diupload
- Cek permission folder `uploads/` sudah **755**
- Cek MAX_FILE_SIZE di php.ini Hostinger (hPanel → PHP Configuration → upload_max_filesize)

### Admin login masih error
- Buka phpMyAdmin Hostinger → tabel `admins` → lihat kolom `password`
- Pastikan value-nya diawali `$2y$10$...` (format bcrypt PHP)
- Jalankan ulang query UPDATE password

---

## 📋 Checklist Deploy

- [ ] Edit `config/database.php` dengan kredensial Hostinger
- [ ] Buat database di hPanel
- [ ] Import `database.sql` via phpMyAdmin Hostinger
- [ ] Update password hash admin di tabel `admins`
- [ ] Upload & extract ZIP ke `public_html/`
- [ ] Set permission `uploads/` → 755
- [ ] Test buka website publik
- [ ] Test login admin

---

## 🔒 Setelah Deploy — Wajib Dilakukan

1. **Ganti password admin default:**
   Admin Panel → Kelola Admin → Edit → Ganti password `admin123`

2. **Hapus file generate-hash.php** (jika masih ada di server):
   File Manager → hapus file tersebut

3. **Pastikan config/database.php tidak bisa diakses publik:**
   Coba buka `https://namamu.hostingersite.com/config/database.php`
   Harus muncul **403 Forbidden** (sudah dihandle .htaccess)
