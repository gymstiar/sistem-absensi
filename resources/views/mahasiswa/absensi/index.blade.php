<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Saya - Sistem Absensi</title>
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
                            <a class="nav-link" href="/mahasiswa/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/mahasiswa/absensi">
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
                    <h1 class="h2">Absensi Saya</h1>
                    <a href="/mahasiswa/absensi/create" class="btn btn-primary">Absen Sekarang</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Mentor</th>
                                <th>Verifikasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi as $index => $absen)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $absen->tanggal }}</td>
                                <td>
                                    <span class="badge 
                                        @if($absen->status == 'hadir') bg-success
                                        @elseif($absen->status == 'izin') bg-warning
                                        @else bg-danger @endif">
                                        {{ $absen->status }}
                                    </span>
                                </td>
                                <td>{{ $absen->mentor->nama }}</td>
                                <td>
                                    <span class="badge 
                                        @if($absen->status_verifikasi == 'diterima') bg-success
                                        @elseif($absen->status_verifikasi == 'ditolak') bg-danger
                                        @else bg-secondary @endif">
                                        {{ $absen->status_verifikasi }}
                                    </span>
                                </td>
                                <td>{{ $absen->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>