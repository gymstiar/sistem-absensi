<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas - Sistem Absensi</title>
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
        .task-card {
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        .task-card.late {
            border-left-color: #dc3545;
        }
        .task-card.submitted {
            border-left-color: #28a745;
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
                            <a class="nav-link active" href="/mahasiswa/tugas">
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
                    <h1 class="h2">Daftar Tugas</h1>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFilter">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Statistik Tugas -->
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
                                <h5>{{ $tugas->where('sudah_dikumpulkan', true)->count() }}</h5>
                                <small>Sudah Dikumpulkan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center py-3">
                                <h5>{{ $tugas->where('sudah_dikumpulkan', false)->where('batas_waktu', '>=', now())->count() }}</h5>
                                <small>Belum Dikumpulkan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body text-center py-3">
                                <h5>{{ $tugas->where('sudah_dikumpulkan', false)->where('batas_waktu', '<', now())->count() }}</h5>
                                <small>Terlambat</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List Tugas -->
                <div class="row">
                    @foreach($tugas as $tugasItem)
                    @php
                        $sudahDikumpulkan = $tugasItem->sudah_dikumpulkan;
                        $terlambat = !$sudahDikumpulkan && $tugasItem->batas_waktu < now();
                        $pengumpulan = $sudahDikumpulkan ? $tugasItem->pengumpulan->first() : null;
                    @endphp
                    
                    <div class="col-md-6 mb-4">
                        <div class="card task-card 
                            @if($sudahDikumpulkan) submitted
                            @elseif($terlambat) late
                            @endif">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $tugasItem->judul_tugas }}</h6>
                                <span class="badge 
                                    @if($sudahDikumpulkan) bg-success
                                    @elseif($terlambat) bg-danger
                                    @else bg-warning @endif">
                                    @if($sudahDikumpulkan) Sudah Dikumpulkan
                                    @elseif($terlambat) Terlambat
                                    @else Belum Dikumpulkan
                                    @endif
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-chalkboard-teacher"></i> 
                                        {{ $tugasItem->mentor->nama }}
                                    </small>
                                </div>
                                
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 
                                        Batas: {{ $tugasItem->batas_waktu->format('d M Y H:i') }}
                                    </small>
                                </div>

                                @if($tugasItem->deskripsi)
                                <div class="mb-2">
                                    <small class="text-muted">Deskripsi:</small>
                                    <p class="mb-1 small">{{ Str::limit($tugasItem->deskripsi, 100) }}</p>
                                </div>
                                @endif

                                @if($tugasItem->file_tugas)
                                <div class="mb-2">
                                    <small class="text-muted">File Tugas:</small>
                                    <br>
                                    <a href="{{ Storage::url($tugasItem->file_tugas) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="fas fa-download"></i> Download File Tugas
                                    </a>
                                </div>
                                @endif

                                @if($sudahDikumpulkan && $pengumpulan)
                                <div class="mb-2">
                                    <small class="text-muted">Status Pengumpulan:</small>
                                    <br>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Sudah dikumpulkan
                                    </span>
                                    <small class="d-block mt-1">
                                        Tanggal: {{ $pengumpulan->tanggal_kumpul->format('d M Y H:i') }}
                                    </small>
                                    @if($pengumpulan->nilai)
                                    <small class="d-block mt-1">
                                        Nilai: <strong>{{ $pengumpulan->nilai }}</strong>
                                    </small>
                                    @endif
                                </div>
                                @endif

                                <div class="d-flex gap-2 mt-3">
                                    <!-- Button Lihat Detail -->
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetail{{ $tugasItem->id_tugas }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>

                                    <!-- Button Kumpulkan -->
                                    @if(!$sudahDikumpulkan)
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalKumpul{{ $tugasItem->id_tugas }}">
                                        <i class="fas fa-upload"></i> Kumpulkan
                                    </button>
                                    @else
                                    <span class="text-success small align-self-center">
                                        <i class="fas fa-check-circle"></i> Sudah Dikumpulkan
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail Tugas -->
                    <div class="modal fade" id="modalDetail{{ $tugasItem->id_tugas }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Tugas: {{ $tugasItem->judul_tugas }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Informasi Tugas</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Mentor</strong></td>
                                                    <td>{{ $tugasItem->mentor->nama }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Mata Kuliah</strong></td>
                                                    <td>{{ $tugasItem->mentor->mata_kuliah }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kelas</strong></td>
                                                    <td>{{ $tugasItem->kelas->nama_kelas }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Upload</strong></td>
                                                    <td>{{ $tugasItem->tanggal_upload->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Batas Waktu</strong></td>
                                                    <td class="@if($terlambat) text-danger @endif">
                                                        {{ $tugasItem->batas_waktu->format('d M Y H:i') }}
                                                        @if($terlambat)
                                                        <br><small class="text-danger">(Terlambat)</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Status Pengumpulan</h6>
                                            @if($sudahDikumpulkan && $pengumpulan)
                                                <div class="alert alert-success">
                                                    <strong><i class="fas fa-check-circle"></i> Sudah Dikumpulkan</strong>
                                                    <br>
                                                    <small>
                                                        Tanggal: {{ $pengumpulan->tanggal_kumpul->format('d M Y H:i') }}<br>
                                                        @if($pengumpulan->nilai)
                                                        Nilai: <strong>{{ $pengumpulan->nilai }}</strong>
                                                        @if($pengumpulan->catatan_mentor)
                                                        <br>Catatan: {{ $pengumpulan->catatan_mentor }}
                                                        @endif
                                                        @else
                                                        Status: <span class="badge bg-warning">Belum Dinilai</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <strong><i class="fas fa-clock"></i> Belum Dikumpulkan</strong>
                                                    <br>
                                                    <small>
                                                        @if($terlambat)
                                                            <span class="text-danger">Batas waktu telah terlampaui</span>
                                                        @else
                                                            Sisa waktu: 
                                                            <strong>{{ $tugasItem->batas_waktu->diffForHumans() }}</strong>
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6>Deskripsi Tugas</h6>
                                            <div class="border p-3 rounded bg-light">
                                                {!! nl2br(e($tugasItem->deskripsi)) !!}
                                            </div>
                                        </div>
                                    </div>

                                    @if($tugasItem->file_tugas)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6>File Tugas dari Mentor</h6>
                                            <div class="d-flex gap-2 align-items-center">
                                                <i class="fas fa-file-alt text-primary"></i>
                                                <span>{{ basename($tugasItem->file_tugas) }}</span>
                                                <a href="{{ Storage::url($tugasItem->file_tugas) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary ms-auto">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($sudahDikumpulkan && $pengumpulan)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6>File yang Dikumpulkan</h6>
                                            <div class="d-flex gap-2 align-items-center">
                                                <i class="fas fa-file-upload text-success"></i>
                                                <span>{{ basename($pengumpulan->file_dikumpulkan) }}</span>
                                                <a href="{{ Storage::url($pengumpulan->file_dikumpulkan) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-success ms-auto">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </div>
                                            @if($pengumpulan->catatan_mentor)
                                            <div class="mt-2">
                                                <small class="text-muted">Catatan Mentor:</small>
                                                <p class="mb-0 small border p-2 rounded">{{ $pengumpulan->catatan_mentor }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    @if(!$sudahDikumpulkan)
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKumpul{{ $tugasItem->id_tugas }}">
                                        Kumpulkan Tugas
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Kumpulkan Tugas -->
                    <div class="modal fade" id="modalKumpul{{ $tugasItem->id_tugas }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Kumpulkan Tugas: {{ $tugasItem->judul_tugas }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/mahasiswa/tugas/{{ $tugasItem->id_tugas }}/kumpulkan" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <small>
                                                <i class="fas fa-info-circle"></i>
                                                <strong>Informasi:</strong><br>
                                                • Batas waktu: {{ $tugasItem->batas_waktu->format('d M Y H:i') }}<br>
                                                • Format file: PDF, DOC, DOCX, ZIP, JPG, PNG<br>
                                                • Maksimal size: 2MB
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="file_dikumpulkan" class="form-label">File Tugas <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="file_dikumpulkan" name="file_dikumpulkan" required>
                                            <div class="form-text">Pilih file tugas yang akan dikumpulkan</div>
                                        </div>

                                        @if($tugasItem->file_tugas)
                                        <div class="mb-3">
                                            <label class="form-label">File Tugas dari Mentor</label>
                                            <div>
                                                <a href="{{ Storage::url($tugasItem->file_tugas) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download File Tugas
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload"></i> Kumpulkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($tugas->count() == 0)
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada tugas</h4>
                    <p class="text-muted">Belum ada tugas yang diberikan untuk kelas Anda.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="modalFilter" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterAll" checked>
                        <label class="form-check-label" for="filterAll">Semua Tugas</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterActive">
                        <label class="form-check-label" for="filterActive">Belum Dikumpulkan</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterSubmitted">
                        <label class="form-check-label" for="filterSubmitted">Sudah Dikumpulkan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filterStatus" id="filterLate">
                        <label class="form-check-label" for="filterLate">Terlambat</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterRadios = document.querySelectorAll('input[name="filterStatus"]');
            
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const filterValue = this.id.replace('filter', '').toLowerCase();
                    const cards = document.querySelectorAll('.task-card');
                    
                    cards.forEach(card => {
                        if (filterValue === 'all') {
                            card.parentElement.style.display = 'block';
                        } else if (filterValue === 'active') {
                            if (!card.classList.contains('submitted') && !card.classList.contains('late')) {
                                card.parentElement.style.display = 'block';
                            } else {
                                card.parentElement.style.display = 'none';
                            }
                        } else if (filterValue === 'submitted') {
                            if (card.classList.contains('submitted')) {
                                card.parentElement.style.display = 'block';
                            } else {
                                card.parentElement.style.display = 'none';
                            }
                        } else if (filterValue === 'late') {
                            if (card.classList.contains('late')) {
                                card.parentElement.style.display = 'block';
                            } else {
                                card.parentElement.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>