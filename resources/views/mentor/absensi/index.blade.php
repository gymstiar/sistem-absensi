<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi - Sistem Absensi</title>
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
        .action-buttons {
            white-space: nowrap;
        }
        .foto-bukti {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
                            <a class="nav-link active" href="/mentor/absensi">
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
                    <h1 class="h2">Data Absensi</h1>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFilter">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
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

                <!-- Statistik Absensi -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center py-3">
                                <h5>{{ $absensi->count() }}</h5>
                                <small>Total Absensi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center py-3">
                                <h5>{{ $absensi->where('status_verifikasi', 'diterima')->count() }}</h5>
                                <small>Diterima</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center py-3">
                                <h5>{{ $absensi->where('status_verifikasi', 'pending')->count() }}</h5>
                                <small>Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body text-center py-3">
                                <h5>{{ $absensi->where('status_verifikasi', 'ditolak')->count() }}</h5>
                                <small>Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Absensi Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Mahasiswa</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th width="200" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensi as $index => $absen)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $absen->mahasiswa->nama }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $absen->mahasiswa->nim }}</small>
                                        </td>
                                        <td>{{ $absen->tanggal->format('d M Y') }}</td>
                                        <td>{{ $absen->waktu_input->format('H:i') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($absen->status == 'hadir') bg-success
                                                @elseif($absen->status == 'izin') bg-warning
                                                @else bg-danger @endif">
                                                {{ $absen->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($absen->status_verifikasi == 'diterima') bg-success
                                                @elseif($absen->status_verifikasi == 'ditolak') bg-danger
                                                @else bg-warning @endif">
                                                {{ $absen->status_verifikasi }}
                                            </span>
                                            @if($absen->keterangan)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($absen->keterangan, 30) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <!-- Button Lihat Detail -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalDetail{{ $absen->id_absen }}"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Button Verifikasi (hanya untuk status pending) -->
                                                @if($absen->status_verifikasi == 'pending')
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalTerima{{ $absen->id_absen }}"
                                                            title="Terima Absensi">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalTolak{{ $absen->id_absen }}"
                                                            title="Tolak Absensi">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                @else
                                                <span class="text-muted small">Terverifikasi</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($absensi->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada absensi</h4>
                            <p class="text-muted">Belum ada mahasiswa yang melakukan absensi.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals untuk setiap absensi -->
    @foreach($absensi as $absen)
    
    <!-- Modal Detail Absensi -->
    <div class="modal fade" id="modalDetail{{ $absen->id_absen }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Mahasiswa</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td width="40%"><strong>Nama</strong></td>
                                    <td>{{ $absen->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIM</strong></td>
                                    <td>{{ $absen->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>{{ $absen->mahasiswa->kelas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td>{{ $absen->tanggal->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Absen</strong></td>
                                    <td>{{ $absen->waktu_input->format('H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Status Absensi</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td width="40%"><strong>Status</strong></td>
                                    <td>
                                        <span class="badge 
                                            @if($absen->status == 'hadir') bg-success
                                            @elseif($absen->status == 'izin') bg-warning
                                            @else bg-danger @endif">
                                            {{ $absen->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Verifikasi</strong></td>
                                    <td>
                                        <span class="badge 
                                            @if($absen->status_verifikasi == 'diterima') bg-success
                                            @elseif($absen->status_verifikasi == 'ditolak') bg-danger
                                            @else bg-warning @endif">
                                            {{ $absen->status_verifikasi }}
                                        </span>
                                    </td>
                                </tr>
                                @if($absen->keterangan)
                                <tr>
                                    <td><strong>Keterangan</strong></td>
                                    <td>{{ $absen->keterangan }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Foto Bukti -->
                    @if($absen->foto_bukti && $absen->status == 'hadir')
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Foto Bukti Kehadiran</h6>
                            <div class="text-center">
                                <img src="{{ Storage::url($absen->foto_bukti) }}" 
                                     alt="Foto Bukti Absensi" 
                                     class="foto-bukti img-fluid mb-3"
                                     onerror="this.style.display='none'">
                                <br>
                                <a href="{{ Storage::url($absen->foto_bukti) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-expand"></i> Lihat Full Size
                                </a>
                                <a href="{{ Storage::url($absen->foto_bukti) }}" 
                                   download 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @elseif($absen->status != 'hadir')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informasi:</strong> 
                                Tidak ada foto bukti untuk status {{ $absen->status }}.
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Peringatan:</strong> 
                                Foto bukti tidak tersedia.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    @if($absen->status_verifikasi == 'pending')
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $absen->id_absen }}">
                            <i class="fas fa-check"></i> Terima
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $absen->id_absen }}">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Terima Absensi -->
    <div class="modal fade" id="modalTerima{{ $absen->id_absen }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terima Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/mentor/absensi/{{ $absen->id_absen }}/verifikasi">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Konfirmasi Penerimaan</strong>
                            <p class="mb-0 mt-2">
                                Apakah Anda yakin ingin menerima absensi 
                                <strong>{{ $absen->mahasiswa->nama }}</strong> 
                                pada tanggal {{ $absen->tanggal->format('d M Y') }}?
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan_terima" class="form-label">Keterangan (Optional)</label>
                            <textarea class="form-control" id="keterangan_terima" name="keterangan" rows="3" placeholder="Berikan keterangan jika diperlukan..."></textarea>
                        </div>
                        <input type="hidden" name="status_verifikasi" value="diterima">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Terima Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Absensi -->
    <div class="modal fade" id="modalTolak{{ $absen->id_absen }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/mentor/absensi/{{ $absen->id_absen }}/verifikasi">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Konfirmasi Penolakan</strong>
                            <p class="mb-0 mt-2">
                                Apakah Anda yakin ingin menolak absensi 
                                <strong>{{ $absen->mahasiswa->nama }}</strong> 
                                pada tanggal {{ $absen->tanggal->format('d M Y') }}?
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan_tolak" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="keterangan_tolak" name="keterangan" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                            <div class="form-text">Alasan penolakan wajib diisi.</div>
                        </div>
                        <input type="hidden" name="status_verifikasi" value="ditolak">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Tolak Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Filter -->
    <div class="modal fade" id="modalFilter" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterAll" checked>
                        <label class="form-check-label" for="filterAll">Semua Absensi</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterPending">
                        <label class="form-check-label" for="filterPending">Pending</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterDiterima">
                        <label class="form-check-label" for="filterDiterima">Diterima</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterDitolak">
                        <label class="form-check-label" for="filterDitolak">Ditolak</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            // Simple filter functionality
            const filterRadios = document.querySelectorAll('input[name="filterStatus"]');
            
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const filterValue = this.id.replace('filter', '').toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const statusCell = row.cells[5]; // Kolom verifikasi
                        const statusText = statusCell.textContent.trim().toLowerCase();
                        
                        if (filterValue === 'all') {
                            row.style.display = '';
                        } else if (filterValue === 'pending' && statusText.includes('pending')) {
                            row.style.display = '';
                        } else if (filterValue === 'diterima' && statusText.includes('diterima')) {
                            row.style.display = '';
                        } else if (filterValue === 'ditolak' && statusText.includes('ditolak')) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>