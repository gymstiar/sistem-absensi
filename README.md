# 📚 Sistem Absensi & Pengumpulan Tugas Mahasiswa

**Laravel 12 --- Pure Laravel (Tanpa Starter Kit)**\
Folder: `sistem-absensi`\
Database: `sistem_absensi`

Sistem ini merupakan aplikasi berbasis web untuk mengelola **absensi**
dan **pengumpulan tugas mahasiswa** menggunakan **Laravel murni tanpa
Breeze, Jetstream, atau Fortify**.\
Seluruh fitur autentikasi, role, middleware, dan dashboard dibangun
manual menggunakan session dan hashing bawaan Laravel.

## 🚀 Fitur Utama

### 🔐 1. Autentikasi (Pure Laravel)

-   Login tanpa starter kit.
-   Menggunakan `Hash::make()` & `Hash::check()` untuk password.
-   Session-based login.
-   Logout manual.
-   Redirect otomatis berdasarkan role:
    -   **Admin** → `/admin/dashboard`
    -   **Mentor** → `/mentor/dashboard`
    -   **Mahasiswa** → `/mahasiswa/dashboard`

### 👥 2. Multi-Level User

#### 🛠 Admin

-   Kelola mahasiswa\
-   Kelola mentor\
-   Kelola kelas\
-   Kelola tugas\
-   Kelola absensi\
-   Melihat **rekap data lengkap**

#### 📘 Mentor

-   Input absensi\
-   Lihat daftar absensi\
-   Buat dan kelola tugas\
-   Nilai tugas mahasiswa

#### 🎓 Mahasiswa

-   Melakukan absensi\
-   Upload file tugas\
-   Melihat riwayat tugas\
-   Melihat status absensi pribadi

### 📊 3. Menu Rekap (Khusus Admin)

Admin dapat melihat: - Rekap seluruh absensi\
- Rekap seluruh tugas\
- Rekap per mahasiswa\
- Rekap per kelas\
- Rekap per tanggal\
- Statistik ringkas: - Total mahasiswa\
- Total mentor\
- Total absensi\
- Total tugas

### 📁 4. Manajemen File

-   Upload foto absensi (Hadir wajib upload foto)

-   Upload tugas (PDF, DOCX, ZIP, JPG, PNG)

-   File disimpan di:

        storage/app/public/

## 🧱 Struktur Proyek (Ringkas)

sistem-absensi/ │ ├── app/ │ ├── Http/Controllers/ │ ├──
Http/Middleware/RoleMiddleware.php │ ├── Models/ │ ├── database/ │ ├──
migrations/ │ └── seeders/UserSeeder.php │ ├── resources/views/ │ ├──
auth/ │ ├── admin/ │ ├── mentor/ │ └── mahasiswa/ │ └── routes/web.php

## ⚙️ Instalasi Proyek

### 1️⃣ Clone Repository

git clone https://github.com/yourusername/sistem-absensi.git 

cd sistem-absensi

### 2️⃣ Install Dependencies

composer install

### 3️⃣ Copy File .env

cp .env.example .env

### 4️⃣ Generate Key

php artisan key:generate

### 5️⃣ Buat Database MySQL

Database: sistem_absensi

Kemudian sesuaikan .env:

DB_DATABASE=sistem_absensi

DB_USERNAME=root

DB_PASSWORD=

### 6️⃣ Migrasi Database

php artisan migrate

### 7️⃣ Jalankan Seeder

php artisan db:seed

## 🔑 Akun Login Default

  Role          Username     Password
  ------------- ------------ ----------
  Admin         admin        password
  Mentor        mentor       password
  Mahasiswa 1   mahasiswa1   password
  Mahasiswa 2   mahasiswa2   password

## 🔗 URL Dashboard

Admin: /admin/dashboard /admin/rekap /admin/mahasiswa /admin/mentor
/admin/tugas /admin/absensi

Mentor: /mentor/dashboard /mentor/tugas /mentor/absensi

Mahasiswa: /mahasiswa/dashboard /mahasiswa/tugas /mahasiswa/absensi

## 📌 Sistem Role & Middleware

role: admin \| mentor \| mahasiswa

Route contoh: Route::middleware(\['role:admin'\])-\>group(function () {
Route::get('/admin/dashboard', \[AdminController::class, 'dashboard'\]);
});

## 🎯 Tujuan Pengembangan

-   Implementasi autentikasi Laravel tanpa starter kit\
-   Belajar RBAC manual\
-   Sistem absensi & tugas ringan

## 🤝 Kontribusi

Pull Request dan Issue sangat diterima.

## 📄 Lisensi

MIT License
