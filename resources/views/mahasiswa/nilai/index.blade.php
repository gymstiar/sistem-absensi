<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Saya - Sistem Absensi</title>
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
                            <a class="nav-link active" href="/mahasiswa/nilai">
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
                    <h1 class="h2">Nilai Saya</h1>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tugas</th>
                                <th>Tanggal Kumpul</th>
                                <th>Nilai</th>
                                <th>Catatan Mentor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilai as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tugas->judul_tugas }}</td>
                                <td>{{ $item->tanggal_kumpul }}</td>
                                <td>
                                    <span class="badge 
                                        @if($item->nilai >= 85) bg-success
                                        @elseif($item->nilai >= 70) bg-warning
                                        @else bg-danger @endif">
                                        {{ $item->nilai }}
                                    </span>
                                </td>
                                <td>{{ $item->catatan_mentor ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($nilai->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h4>{{ number_format($nilai->avg('nilai'), 2) }}</h4>
                                <p>Rata-rata Nilai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h4>{{ $nilai->max('nilai') }}</h4>
                                <p>Nilai Tertinggi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <h4>{{ $nilai->count() }}</h4>
                                <p>Total Tugas Dinilai</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>