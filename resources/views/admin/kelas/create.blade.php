<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas - Sistem Absensi</title>
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
                            <a class="nav-link" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/mahasiswa">
                                <i class="fas fa-users"></i> Data Mahasiswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/mentor">
                                <i class="fas fa-chalkboard-teacher"></i> Data Mentor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/kelas">
                                <i class="fas fa-school"></i> Data Kelas
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-chart-bar"></i> Rekap Data
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/admin/rekap/absensi">Rekap Absensi</a></li>
                                <li><a class="dropdown-item" href="/admin/rekap/tugas">Rekap Tugas</a></li>
                            </ul>
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
                    <h1 class="h2">Tambah Kelas</h1>
                </div>

                <form method="POST" action="/admin/kelas">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_mentor" class="form-label">Mentor</label>
                                <select class="form-control" id="id_mentor" name="id_mentor" required>
                                    <option value="">Pilih Mentor</option>
                                    @foreach($mentors as $mentor)
                                        <option value="{{ $mentor->id_mentor }}">{{ $mentor->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="/admin/kelas" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>