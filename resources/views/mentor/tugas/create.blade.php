<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas - Sistem Absensi</title>
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
                            <a class="nav-link" href="/mentor/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mentor/tugas">
                                <i class="fas fa-tasks"></i> Data Tugas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mentor/absensi">
                                <i class="fas fa-clipboard-check"></i> Data Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mentor/penilaian">
                                <i class="fas fa-check-circle"></i> Penilaian
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
                    <h1 class="h2">Buat Tugas</h1>
                </div>

                <form method="POST" action="/mentor/tugas" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="judul_tugas" class="form-label">Judul Tugas</label>
                                <input type="text" class="form-control" id="judul_tugas" name="judul_tugas" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_kelas" class="form-label">Kelas</label>
                                <select class="form-control" id="id_kelas" name="id_kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $kelasItem)
                                        <option value="{{ $kelasItem->id_kelas }}">{{ $kelasItem->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="batas_waktu" class="form-label">Batas Waktu</label>
                                <input type="datetime-local" class="form-control" id="batas_waktu" name="batas_waktu" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Tugas</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="file_tugas" class="form-label">File Tugas (Optional)</label>
                                <input type="file" class="form-control" id="file_tugas" name="file_tugas">
                                <small class="text-muted">Format: PDF, DOC, DOCX, ZIP (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Simpan Tugas</button>
                    <a href="/mentor/tugas" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>