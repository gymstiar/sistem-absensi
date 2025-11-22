# 📚 Sistem Absensi & Pengumpulan Tugas Mahasiswa

**Laravel 12 — Pure Laravel (Tanpa Starter Kit)**
Folder proyek: `sistem-absensi`
Database: `sistem_absensi`

Aplikasi ini merupakan sistem **absensi** dan **pengumpulan tugas mahasiswa** berbasis web yang dibuat menggunakan **Laravel murni tanpa Breeze/Jetstream/Fortify**.
Seluruh fitur login, role, middleware, dan dashboard dibangun manual menggunakan **session** dan **hashing** Laravel.

---

# 🚀 Fitur Utama

## 🔐 1. Autentikasi (Pure Laravel)

* Login tanpa starter kit.
* Menggunakan `Hash::make()` dan `Hash::check()`.
* Session authentication.
* Logout manual.
* Redirect otomatis berdasarkan role:

  * **Admin** → `/admin/dashboard`
  * **Mentor** → `/mentor/dashboard`
  * **Mahasiswa** → `/mahasiswa/dashboard`

---

## 👥 2. Multi-Level User (Role)

### 🛠 Admin

* Kelola mahasiswa
* Kelola mentor
* Kelola kelas
* Kelola tugas
* Kelola absensi
* Melihat **rekap lengkap** dari semua data

### 📘 Mentor

* Input dan mengelola absensi
* Membuat dan mengelola tugas
* Memberikan nilai tugas mahasiswa
* Melihat absensi mahasiswa

### 🎓 Mahasiswa

* Melakukan absensi (Hadir/Izin/Sakit)
* Upload file tugas
* Melihat riwayat tugas
* Melihat status absensi pribadi

---

## 📊 3. Menu Rekap (Admin Only)

Admin dapat melihat rekap:

* Seluruh data absensi
* Seluruh data tugas
* Rekap per kelas
* Rekap per mahasiswa
* Rekap per tanggal
* Statistik:

  * Total mahasiswa
  * Total mentor
  * Total absensi
  * Total tugas

---

## 📁 4. Manajemen File

* Upload foto absensi (Hadir wajib upload foto)
* Upload file tugas (PDF, DOCX, ZIP, JPG, PNG)
* Semua file tersimpan di:

```
storage/app/public/
```

---

# 🧱 Struktur Proyek (Ringkas)

```
sistem-absensi/
│
├── app/
│   ├── Http/Controllers/
│   ├── Http/Middleware/RoleMiddleware.php
│   ├── Models/
│
├── database/
│   ├── migrations/
│   └── seeders/UserSeeder.php
│
├── resources/views/
│   ├── auth/
│   ├── admin/
│   ├── mentor/
│   └── mahasiswa/
│
└── routes/web.php
```

---

# ⚙️ Instalasi & Menjalankan Proyek

## 1️⃣ Clone Repository

```bash
git clone https://github.com/gymstiar/sistem-absensi.git
cd sistem-absensi
```

## 2️⃣ Install Dependency Composer

```bash
composer install
```

## 3️⃣ Copy File .env

```bash
cp .env.example .env
```

## 4️⃣ Generate APP_KEY

```bash
php artisan key:generate
```

## 5️⃣ Buat Database MySQL

Buat database bernama **sistem_absensi**.

Kemudian edit `.env`:

```
DB_DATABASE=sistem_absensi
DB_USERNAME=root
DB_PASSWORD=
```

## 6️⃣ Migrasi Database

```bash
php artisan migrate
```

## 7️⃣ Jalankan Seeder

```bash
php artisan db:seed
```

## 8️⃣ Buat Storage Link

```bash
php artisan storage:link
```

## 9️⃣ Jalankan Laravel

```bash
php artisan serve
```

Aplikasi berjalan di:
➡ [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

# 🔑 Akun Login Default

```
•  Admin: username=admin, password=password
•  Mentor: username=mentor, password=password
•  Mahasiswa 1: username=mahasiswa1, password=password
•  Mahasiswa 2: username=mahasiswa2, password=password
```
---

# 🔗 URL Dashboard

### 👑 Admin

```
/admin/dashboard
/admin/rekap
/admin/mahasiswa
/admin/mentor
/admin/tugas
/admin/absensi
```

### 🎓 Mahasiswa

```
/mahasiswa/dashboard
/mahasiswa/tugas
/mahasiswa/absensi
```

### 📘 Mentor

```
/mentor/dashboard
/mentor/tugas
/mentor/absensi
```

---

# 🛡️ Sistem Role & Middleware

Role di aplikasi:

```
admin | mentor | mahasiswa
```

Contoh penggunaan di `web.php`:

```php
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
```

# 🧪 Status Pengembangan
```
Saat ini beberapa fitur masih belum berjalan 100% dan masih dalam tahap pengembangan 
Project ini sangat terbuka untuk dikembangkan lebih lanjut sesuai kreativitas masing-masing, baik dari sisi frontend, backend, maupun fitur tambahan.
```

# 🤝 Kontribusi

Pull Request dan Issue sangat diterima untuk pengembangan lebih lanjut.

---

# 📄 Lisensi

**MIT License**

---

# © Copyright

```
© 2025 Sistem Absensi & Pengumpulan Tugas — Developed by gymstiar.
All rights reserved.

Project ini hanya untuk kebutuhan pembelajaran, pengembangan, dan keperluan non-komersial.
Setiap penggunaan ulang, modifikasi, atau distribusi kode wajib mencantumkan kredit kepada pengembang asli.
```
