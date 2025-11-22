<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Sistem Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky pt-3">
                    <h4 class="text-white text-center mb-4">Sistem Absensi</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="/mahasiswa/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mahasiswa/absensi">
                                <i class="fas fa-clipboard-check"></i> Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mahasiswa/tugas">
                                <i class="fas fa-tasks"></i> Tugas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mahasiswa/nilai">
                                <i class="fas fa-chart-line"></i> Nilai
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard Mahasiswa</h1>
                    <span class="text-muted">Selamat datang, {{ auth()->user()->mahasiswa->nama }}</span>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $absensiBulanIni }}</h4>
                                        <p>Absensi Bulan Ini</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clipboard-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $tugasAktif }}</h4>
                                        <p>Tugas Aktif</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-tasks fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $tugasTerkumpul }}</h4>
                                        <p>Tugas Terkumpul</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Mahasiswa</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>NIM:</strong> {{ auth()->user()->mahasiswa->nim }}</p>
                                <p><strong>Nama:</strong> {{ auth()->user()->mahasiswa->nama }}</p>
                                <p><strong>Kelas:</strong> {{ auth()->user()->mahasiswa->kelas }}</p>
                                <p><strong>Username:</strong> {{ auth()->user()->username }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Aksi Cepat</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="/mahasiswa/absensi/create" class="btn btn-outline-primary">Absen Sekarang</a>
                                    <a href="/mahasiswa/tugas" class="btn btn-outline-success">Lihat Tugas</a>
                                    <a href="/mahasiswa/nilai" class="btn btn-outline-info">Lihat Nilai</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifikasi Hari Ini -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Aktivitas Terbaru</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $absensiHariIni = \App\Models\Absensi::where('id_mahasiswa', auth()->user()->mahasiswa->id_mahasiswa)
                                        ->whereDate('tanggal', today())
                                        ->first();
                                @endphp
                                
                                @if($absensiHariIni)
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> Anda sudah absen hari ini dengan status: 
                                        <strong>{{ $absensiHariIni->status }}</strong>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-circle"></i> Anda belum absen hari ini. 
                                        <a href="/mahasiswa/absensi/create" class="alert-link">Klik untuk absen</a>
                                    </div>
                                @endif

                                @php
                                    $tugasDeadline = \App\Models\Tugas::whereHas('kelas', function($query) {
                                        $query->where('nama_kelas', auth()->user()->mahasiswa->kelas);
                                    })->where('batas_waktu', '>=', now())
                                      ->where('batas_waktu', '<=', now()->addDays(3))
                                      ->count();
                                @endphp

                                @if($tugasDeadline > 0)
                                    <div class="alert alert-info">
                                        <i class="fas fa-clock"></i> Ada <strong>{{ $tugasDeadline }}</strong> tugas dengan deadline mendekati. 
                                        <a href="/mahasiswa/tugas" class="alert-link">Cek sekarang</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>