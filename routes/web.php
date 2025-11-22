<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to appropriate dashboard
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'mentor':
                return redirect('/mentor/dashboard');
            case 'mahasiswa':
                return redirect('/mahasiswa/dashboard');
        }
    }
    return redirect('/login');
});

// Admin Routes
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard']);
    
    // Mahasiswa Management
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswaIndex']);
    Route::get('/mahasiswa/create', [AdminController::class, 'mahasiswaCreate']);
    Route::post('/mahasiswa', [AdminController::class, 'mahasiswaStore']);
    
    // Mentor Management
    Route::get('/mentor', [AdminController::class, 'mentorIndex']);
    Route::get('/mentor/create', [AdminController::class, 'mentorCreate']);
    Route::post('/mentor', [AdminController::class, 'mentorStore']);
    
    // Kelas Management
    Route::get('/kelas', [AdminController::class, 'kelasIndex']);
    Route::get('/kelas/create', [AdminController::class, 'kelasCreate']);
    Route::post('/kelas', [AdminController::class, 'kelasStore']);
    
    // Rekap Data
    Route::get('/rekap/absensi', [AdminController::class, 'rekapAbsensi']);
    Route::get('/rekap/tugas', [AdminController::class, 'rekapTugas']);
});

// Mentor Routes
Route::middleware(['auth', RoleMiddleware::class . ':mentor'])->prefix('mentor')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mentorDashboard']);
    
    // Tugas Management
    Route::get('/tugas', [MentorController::class, 'tugasIndex']);
    Route::get('/tugas/create', [MentorController::class, 'tugasCreate']);
    Route::post('/tugas', [MentorController::class, 'tugasStore']);
    Route::put('/tugas/{id}/update', [MentorController::class, 'tugasUpdate']);
    Route::delete('/tugas/{id}/delete', [MentorController::class, 'tugasDelete']);
    
    // Absensi Management
    Route::get('/absensi', [MentorController::class, 'absensiIndex']);
    Route::post('/absensi/{id}/verifikasi', [MentorController::class, 'verifikasiAbsensi']);
    
    // Penilaian
    Route::get('/penilaian', [MentorController::class, 'penilaianIndex']);
    Route::post('/penilaian/{id}/nilai', [MentorController::class, 'beriNilai']);

    // Di dalam group mentor routes
    Route::put('/tugas/{id}/update', [MentorController::class, 'tugasUpdate']);
    Route::delete('/tugas/{id}/delete', [MentorController::class, 'tugasDelete']);
    Route::get('/tugas/{id}/download', [MentorController::class, 'downloadFileTugas']);
    Route::get('/pengumpulan/{id}/download', [MentorController::class, 'downloadFilePengumpulan']);
    Route::get('/pengumpulan/{id}/detail', [MentorController::class, 'detailPengumpulan']);
    Route::get('/tugas/statistik', [MentorController::class, 'statistikTugas']);
    Route::get('/tugas/{id}/export', [MentorController::class, 'exportTugas']);
    Route::get('/kelas/{id}/tugas', [MentorController::class, 'getTugasByKelas']);
    Route::get('/tugas/{id}/json', [MentorController::class, 'tugasJson']);

});

// Mahasiswa Routes
Route::middleware(['auth', RoleMiddleware::class . ':mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswaDashboard']);
    
    // Absensi
    Route::get('/absensi', [MahasiswaController::class, 'absensiIndex']);
    Route::get('/absensi/create', [MahasiswaController::class, 'absensiCreate']);
    Route::post('/absensi', [MahasiswaController::class, 'absensiStore']);
    
    // Tugas
    Route::get('/tugas', [MahasiswaController::class, 'tugasIndex']);
    Route::post('/tugas/{id}/kumpulkan', [MahasiswaController::class, 'kumpulkanTugas']);
    
    // Nilai
    Route::get('/nilai', [MahasiswaController::class, 'nilaiIndex']);
});