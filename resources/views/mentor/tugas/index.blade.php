<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tugas - Sistem Absensi</title>
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
        .status-badge {
            font-size: 0.75rem;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
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
                            <a class="nav-link active" href="/mentor/tugas">
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
                    <h1 class="h2">Data Tugas</h1>
                    <a href="/mentor/tugas/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Tugas
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center py-3">
                                <h5>{{ $tugas->count() }}</h5>
                                <small>Total Tugas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center py-3">
                                <h5>{{ $tugas->where('batas_waktu', '>=', now())->count() }}</h5>
                                <small>Tugas Aktif</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center py-3">
                                <h5>{{ $tugas->where('batas_waktu', '<', now())->count() }}</h5>
                                <small>Tugas Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center py-3">
                                <h5>{{ $totalPengumpulan }}</h5>
                                <small>Total Pengumpulan</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Tugas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Tugas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Judul Tugas</th>
                                        <th>Kelas</th>
                                        <th>Tanggal Upload</th>
                                        <th>Batas Waktu</th>
                                        <th>Status</th>
                                        <th>Pengumpulan</th>
                                        <th width="150" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tugas as $index => $item)
                                    @php
                                        $isActive = $item->batas_waktu >= now();
                                        $pengumpulanCount = $item->pengumpulan_count;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->judul_tugas }}</strong>
                                            @if($item->file_tugas)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-file"></i> Ada file
                                            </small>
                                            @endif
                                        </td>
                                        <td>{{ $item->kelas->nama_kelas }}</td>
                                        <td>{{ $item->tanggal_upload->format('d M Y') }}</td>
                                        <td class="{{ !$isActive ? 'text-danger' : '' }}">
                                            {{ $item->batas_waktu->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge status-badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $isActive ? 'Aktif' : 'Selesai' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info status-badge">
                                                {{ $pengumpulanCount }} Mahasiswa
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <!-- Button Lihat Detail -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalDetail{{ $item->id_tugas }}"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Button Edit -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEdit{{ $item->id_tugas }}"
                                                        title="Edit Tugas">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Button Hapus -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalHapus{{ $item->id_tugas }}"
                                                        title="Hapus Tugas">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($tugas->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada tugas</h4>
                            <p class="text-muted">Anda belum membuat tugas untuk kelas Anda.</p>
                            <a href="/mentor/tugas/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Tugas Pertama
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals untuk setiap tugas -->
    @foreach($tugas as $item)
    @php
        $isActive = $item->batas_waktu >= now();
        $pengumpulanCount = $item->pengumpulan_count;
    @endphp

    <!-- Modal Detail Tugas -->
    <div class="modal fade" id="modalDetail{{ $item->id_tugas }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Tugas: {{ $item->judul_tugas }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Tugas</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td width="40%"><strong>Judul Tugas</strong></td>
                                    <td>{{ $item->judul_tugas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>{{ $item->kelas->nama_kelas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Upload</strong></td>
                                    <td>{{ $item->tanggal_upload->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Waktu</strong></td>
                                    <td class="{{ !$isActive ? 'text-danger' : '' }}">
                                        {{ $item->batas_waktu->format('d M Y H:i') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $isActive ? 'Aktif' : 'Selesai' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Pengumpulan</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $pengumpulanCount }} Mahasiswa</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>File Tugas</h6>
                            @if($item->file_tugas)
                                <div class="alert alert-info">
                                    <i class="fas fa-file"></i> 
                                    <strong>File Terlampir:</strong>
                                    <br>
                                    <a href="{{ Storage::url($item->file_tugas) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-circle"></i> 
                                    Tidak ada file terlampir
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Deskripsi Tugas</h6>
                            <div class="border p-3 rounded bg-light">
                                @if($item->deskripsi)
                                    {!! nl2br(e($item->deskripsi)) !!}
                                @else
                                    <em class="text-muted">Tidak ada deskripsi</em>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($pengumpulanCount > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Statistik Pengumpulan</h6>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body py-2">
                                            <h6>{{ $pengumpulanCount }}</h6>
                                            <small>Total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body py-2">
                                            <h6>{{ $item->pengumpulan->where('nilai', '!=', null)->count() }}</h6>
                                            <small>Sudah Dinilai</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body py-2">
                                            <h6>{{ $item->pengumpulan->where('nilai', null)->count() }}</h6>
                                            <small>Belum Dinilai</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="/mentor/penilaian?tugas={{ $item->id_tugas }}" class="btn btn-primary">
                        <i class="fas fa-list-check"></i> Lihat Pengumpulan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div class="modal fade" id="modalEdit{{ $item->id_tugas }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tugas: {{ $item->judul_tugas }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/mentor/tugas/{{ $item->id_tugas }}/update" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul_tugas" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_tugas" name="judul_tugas" 
                                   value="{{ $item->judul_tugas }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select class="form-control" id="id_kelas" name="id_kelas" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $kelasItem)
                                    <option value="{{ $kelasItem->id_kelas }}" 
                                        {{ $item->id_kelas == $kelasItem->id_kelas ? 'selected' : '' }}>
                                        {{ $kelasItem->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="batas_waktu" class="form-label">Batas Waktu <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="batas_waktu" name="batas_waktu" 
                                   value="{{ $item->batas_waktu->format('Y-m-d\TH:i') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Tugas</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4">{{ $item->deskripsi }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="file_tugas" class="form-label">File Tugas (Optional)</label>
                            <input type="file" class="form-control" id="file_tugas" name="file_tugas">
                            <div class="form-text">
                                @if($item->file_tugas)
                                    File saat ini: 
                                    <a href="{{ Storage::url($item->file_tugas) }}" target="_blank">
                                        {{ basename($item->file_tugas) }}
                                    </a>
                                @else
                                    Tidak ada file saat ini
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Tugas -->
    <div class="modal fade" id="modalHapus{{ $item->id_tugas }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Peringatan!</strong>
                        <p class="mb-0 mt-2">
                            Apakah Anda yakin ingin menghapus tugas "<strong>{{ $item->judul_tugas }}</strong>"?
                        </p>
                        <p class="mb-0">
                            Tugas yang dihapus tidak dapat dikembalikan.
                        </p>
                        @if($pengumpulanCount > 0)
                        <p class="mb-0 text-danger">
                            <strong>Perhatian:</strong> Tugas ini sudah dikumpulkan oleh {{ $pengumpulanCount }} mahasiswa.
                        </p>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="/mentor/tugas/{{ $item->id_tugas }}/delete" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus Tugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-close alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>