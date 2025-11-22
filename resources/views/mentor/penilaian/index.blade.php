<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Tugas - Sistem Absensi</title>
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
        .nilai-badge {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        .file-preview {
            max-width: 100%;
            max-height: 200px;
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
                            <a class="nav-link" href="/mentor/absensi">
                                <i class="fas fa-clipboard-check"></i> Data Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/mentor/penilaian">
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
                    <h1 class="h2">Penilaian Tugas</h1>
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

                <!-- Statistik Penilaian -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center py-3">
                                <h5>{{ $pengumpulan->count() }}</h5>
                                <small>Total Pengumpulan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center py-3">
                                <h5>{{ $pengumpulan->where('nilai', '!=', null)->count() }}</h5>
                                <small>Sudah Dinilai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center py-3">
                                <h5>{{ $pengumpulan->where('nilai', null)->count() }}</h5>
                                <small>Belum Dinilai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center py-3">
                                <h5>{{ number_format($pengumpulan->where('nilai', '!=', null)->avg('nilai') ?? 0, 1) }}</h5>
                                <small>Rata-rata Nilai</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tugas - FIXED -->
                @php
                    // Ambil daftar tugas unik dari data pengumpulan
                    $uniqueTugas = $pengumpulan->pluck('tugas')->unique()->filter();
                @endphp

                @if($uniqueTugas->count() > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="filterTugas" class="form-label">Filter Berdasarkan Tugas</label>
                                <select class="form-select" id="filterTugas" onchange="filterByTugas(this.value)">
                                    <option value="">Semua Tugas</option>
                                    @foreach($uniqueTugas as $tugas)
                                        <option value="{{ $tugas->id_tugas }}" 
                                            {{ request('tugas') == $tugas->id_tugas ? 'selected' : '' }}>
                                            {{ $tugas->judul_tugas }} ({{ $tugas->kelas->nama_kelas ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                @if(request('tugas'))
                                <a href="/mentor/penilaian" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Hapus Filter
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Pengumpulan Tugas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Mahasiswa</th>
                                        <th>Tugas & Kelas</th>
                                        <th>Tanggal Kumpul</th>
                                        <th>Status</th>
                                        <th>Nilai</th>
                                        <th width="180" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengumpulan as $index => $item)
                                    @php
                                        $isLate = $item->tanggal_kumpul > $item->tugas->batas_waktu;
                                        $hasNilai = $item->nilai !== null;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->mahasiswa->nama }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->mahasiswa->nim }}</small>
                                            <br>
                                            <small class="text-muted">{{ $item->mahasiswa->kelas }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $item->tugas->judul_tugas }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->tugas->kelas->nama_kelas }}</small>
                                            <br>
                                            <small class="text-muted">
                                                Batas: {{ $item->tugas->batas_waktu->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            {{ $item->tanggal_kumpul->format('d M Y H:i') }}
                                            @if($isLate)
                                            <br>
                                            <span class="badge bg-danger">Terlambat</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($hasNilai)
                                            <span class="badge bg-success">Sudah Dinilai</span>
                                            @else
                                            <span class="badge bg-warning">Belum Dinilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($hasNilai)
                                            <span class="badge nilai-badge 
                                                @if($item->nilai >= 85) bg-success
                                                @elseif($item->nilai >= 70) bg-warning
                                                @else bg-danger @endif">
                                                {{ $item->nilai }}
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <!-- Button Lihat Detail -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalDetail{{ $item->id_pengumpulan }}"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Button Beri/Edit Nilai -->
                                                <button type="button" 
                                                        class="btn btn-sm {{ $hasNilai ? 'btn-warning' : 'btn-primary' }}" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalNilai{{ $item->id_pengumpulan }}"
                                                        title="{{ $hasNilai ? 'Edit Nilai' : 'Beri Nilai' }}">
                                                    <i class="fas {{ $hasNilai ? 'fa-edit' : 'fa-check' }}"></i>
                                                </button>

                                                <!-- Button Download File -->
                                                @if($item->file_dikumpulkan)
                                                <a href="{{ Storage::url($item->file_dikumpulkan) }}" 
                                                   target="_blank"
                                                   class="btn btn-sm btn-success"
                                                   title="Download File">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($pengumpulan->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada pengumpulan tugas</h4>
                            <p class="text-muted">Mahasiswa belum mengumpulkan tugas untuk dinilai.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals untuk setiap pengumpulan -->
    @foreach($pengumpulan as $item)
    @php
        $hasNilai = $item->nilai !== null;
        $isLate = $item->tanggal_kumpul > $item->tugas->batas_waktu;
    @endphp

    <!-- Modal Detail Pengumpulan -->
    <div class="modal fade" id="modalDetail{{ $item->id_pengumpulan }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengumpulan Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Mahasiswa</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td width="40%"><strong>Nama</strong></td>
                                    <td>{{ $item->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIM</strong></td>
                                    <td>{{ $item->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>{{ $item->mahasiswa->kelas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Kumpul</strong></td>
                                    <td class="{{ $isLate ? 'text-danger' : '' }}">
                                        {{ $item->tanggal_kumpul->format('d M Y H:i') }}
                                        @if($isLate)
                                        <br><small class="text-danger">(Terlambat)</small>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Tugas</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td width="40%"><strong>Judul Tugas</strong></td>
                                    <td>{{ $item->tugas->judul_tugas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>{{ $item->tugas->kelas->nama_kelas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Waktu</strong></td>
                                    <td>{{ $item->tugas->batas_waktu->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        @if($hasNilai)
                                        <span class="badge bg-success">Sudah Dinilai</span>
                                        @else
                                        <span class="badge bg-warning">Belum Dinilai</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($hasNilai)
                                <tr>
                                    <td><strong>Nilai</strong></td>
                                    <td>
                                        <span class="badge 
                                            @if($item->nilai >= 85) bg-success
                                            @elseif($item->nilai >= 70) bg-warning
                                            @else bg-danger @endif">
                                            {{ $item->nilai }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- File yang Dikumpulkan -->
                    <div class="row mt-4">
                    <div class="col-12">
                        <h6>File yang Dikumpulkan</h6>

                        @php
                            $file = $item->file_dikumpulkan;
                            $url = $file ? Storage::url($file) : null;
                            $ext = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null;
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
                        @endphp

                        @if($file)
                        <div class="alert alert-info">
                            <i class="fas fa-file"></i> 
                            <strong>File Terkumpul:</strong> {{ basename($file) }}

                            <div class="mt-2">
                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>

                                <a href="{{ $url }}" download class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>

                            {{-- Preview jika file adalah gambar --}}
                            @if($isImage)
                            <div class="mt-3 text-center">
                                <img src="{{ $url }}" alt="Preview File" class="file-preview img-fluid rounded shadow">
                            </div>
                            @endif
                        </div>

                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i> 
                            Tidak ada file yang dikumpulkan
                        </div>
                        @endif
                    </div>
                </div>
                    <!-- Catatan Mentor -->
                    @if($hasNilai && $item->catatan_mentor)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Catatan Mentor</h6>
                            <div class="border p-3 rounded bg-light">
                                {{ $item->catatan_mentor }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNilai{{ $item->id_pengumpulan }}">
                        <i class="fas fa-edit"></i> {{ $hasNilai ? 'Edit Nilai' : 'Beri Nilai' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Beri/Edit Nilai -->
    <div class="modal fade" id="modalNilai{{ $item->id_pengumpulan }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $hasNilai ? 'Edit Nilai' : 'Beri Nilai' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/mentor/penilaian/{{ $item->id_pengumpulan }}/nilai">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Informasi:</strong>
                            <br>
                            <small>
                                Mahasiswa: <strong>{{ $item->mahasiswa->nama }}</strong>
                                <br>
                                Tugas: <strong>{{ $item->tugas->judul_tugas }}</strong>
                                @if($isLate)
                                <br>
                                <span class="text-danger">⚠️ Pengumpulan terlambat</span>
                                @endif
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="nilai" name="nilai" 
                                   value="{{ $item->nilai ?? '' }}" min="0" max="100" step="0.1" required
                                   placeholder="Masukkan nilai 0-100">
                            <div class="form-text">
                                Skala penilaian: 0-100
                                @if($hasNilai)
                                <br>Nilai sebelumnya: <strong>{{ $item->nilai }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_mentor" class="form-label">Catatan untuk Mahasiswa</label>
                            <textarea class="form-control" id="catatan_mentor" name="catatan_mentor" rows="4" 
                                      placeholder="Berikan catatan, saran, atau feedback untuk mahasiswa...">{{ $item->catatan_mentor ?? '' }}</textarea>
                            <div class="form-text">Catatan ini akan dilihat oleh mahasiswa</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ $hasNilai ? 'Update Nilai' : 'Simpan Nilai' }}
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
                    <h5 class="modal-title">Filter Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterAll" checked>
                        <label class="form-check-label" for="filterAll">Semua</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterBelum">
                        <label class="form-check-label" for="filterBelum">Belum Dinilai</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterSudah">
                        <label class="form-check-label" for="filterSudah">Sudah Dinilai</label>
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

            // Filter by tugas - FIXED
            function filterByTugas(tugasId) {
                if (tugasId) {
                    window.location.href = '/mentor/penilaian?tugas=' + tugasId;
                } else {
                    window.location.href = '/mentor/penilaian';
                }
            }

            // Simple filter functionality
            const filterRadios = document.querySelectorAll('input[name="filterStatus"]');
            
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const filterValue = this.id.replace('filter', '').toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const statusCell = row.cells[4]; // Kolom status
                        const statusText = statusCell.textContent.trim().toLowerCase();
                        
                        if (filterValue === 'all') {
                            row.style.display = '';
                        } else if (filterValue === 'belum' && statusText.includes('belum')) {
                            row.style.display = '';
                        } else if (filterValue === 'sudah' && statusText.includes('sudah')) {
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