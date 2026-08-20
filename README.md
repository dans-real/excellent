# 🎓 UKM-F EXCELLENT — Website PHP Native

Website resmi UKM-F EXCELLENT berbasis PHP Native + MySQL (phpMyAdmin).

---

## 📁 Struktur Folder

```
excellent/
├── index.php              # Beranda (Guest)
├── about.php              # Tentang Kami
├── structure.php          # Struktur Organisasi
├── events.php             # Daftar Events
├── event-detail.php       # Detail Event
├── gallery.php            # Galeri Foto
├── contact.php            # Kontak
│
├── admin/
│   ├── index.php          # Dashboard Admin
│   ├── login.php          # Halaman Login
│   ├── logout.php         # Logout
│   ├── includes/
│   │   ├── sidebar.php    # Sidebar navigasi admin
│   │   └── topbar.php     # Header admin
│   └── pages/
│       ├── events.php     # CRUD Events
│       ├── members.php    # CRUD Anggota
│       ├── gallery.php    # Upload & Kelola Foto
│       ├── settings.php   # Pengaturan Beranda
│       ├── messages.php   # Pesan dari pengunjung
│       └── admins.php     # Kelola Admin (superadmin)
│
├── config/
│   ├── database.php       # ← EDIT konfigurasi DB di sini
│   └── session.php        # Session & auth helper
│
├── includes/
│   ├── functions.php      # Helper functions
│   ├── header.php         # Header HTML publik
│   └── footer.php         # Footer HTML publik
│
├── assets/
│   ├── css/
│   │   ├── style.css      # CSS website publik
│   │   └── admin.css      # CSS admin panel
│   └── js/
│       └── main.js        # JavaScript (dark mode, dll)
│
├── uploads/               # Folder foto (auto-created)
│   ├── gallery/           # Foto galeri
│   ├── members/           # Foto anggota
│   └── hero/              # Foto hero (opsional)
│
├── database.sql           # ← SQL untuk phpMyAdmin
├── .htaccess              # Security rules
├── .gitignore
└── README.md
```

---

## 🚀 Cara Install di XAMPP

### Langkah 1 — Copy folder
```
Salin folder `excellent/` ke:
C:\xampp\htdocs\excellent\
```

### Langkah 2 — Setup Database
1. Buka **phpMyAdmin** → `http://localhost/phpmyadmin`
2. Klik tab **SQL**
3. **Copy-paste** seluruh isi `database.sql` → klik **Go**
4. Database `db_excellent` otomatis terbuat ✅

### Langkah 3 — Konfigurasi (jika perlu)
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');   // default XAMPP
define('DB_NAME', 'db_excellent');
define('DB_USER', 'root');         // default XAMPP
define('DB_PASS', '');             // default XAMPP kosong
define('BASE_URL', 'http://localhost/excellent');
```

### Langkah 4 — Buka Website
- **Website publik:** `http://localhost/excellent/`
- **Admin panel:** `http://localhost/excellent/admin/`
  - Username: `admin`
  - Password: `admin123`

> ⚠️ **Segera ganti password default setelah login pertama!**
> Admin → Kelola Admin → Edit → Ganti Password

---

## 👤 Akun Default

| Field    | Value    |
|----------|----------|
| Username | `admin`  |
| Password | `admin123` |
| Role     | `superadmin` |

---

## ✨ Fitur

### Website Publik (Guest)
- ✅ Beranda dinamis (konten dari DB)
- ✅ Halaman About, Structure, Events, Gallery, Contact
- ✅ Detail event dengan slug URL
- ✅ Filter & search events
- ✅ Gallery foto dengan lightbox
- ✅ Form kontak (tersimpan ke DB)
- ✅ Dark mode (localStorage)
- ✅ Responsive mobile
- ✅ WhatsApp floating button

### Admin Panel
- ✅ Login aman (bcrypt password)
- ✅ Dashboard statistik
- ✅ **Kelola Events** — tambah, edit, hapus, featured
- ✅ **Kelola Anggota** — CRUD + upload foto profil
- ✅ **Kelola Gallery** — upload foto, kategori, lightbox
- ✅ **Pengaturan Beranda** — edit semua teks beranda dari DB
- ✅ **Pesan Masuk** — baca & balas pesan kontak
- ✅ **Kelola Admin** — tambah admin baru (superadmin only)

---

## 🔧 Push ke GitHub

```bash
# Di folder excellent/
git init
git add .
git commit -m "Initial commit — UKM-F EXCELLENT website"
git branch -M main
git remote add origin https://github.com/USERNAME/excellent.git
git push -u origin main
```

> **Catatan:** `config/database.php` ada di `.gitignore`.
> Buat file `config/database.example.php` sebagai template untuk tim.
# excellent

---

## 🔐 Keamanan

- Password admin di-hash dengan **bcrypt**
- Upload file divalidasi MIME type & ukuran (max 5MB)
- Folder `uploads/` diblokir eksekusi PHP via `.htaccess`
- Akses folder `config/` diblokir via `.htaccess`
- Session cookie: `httponly`, `samesite=Lax`

---

## 📞 Kontak

- Instagram: [@ukmf.excellent](https://www.instagram.com/ukmf.excellent)
- WhatsApp: +62 895-803-316-833
- Email: ukmfexcellent@gmail.com
