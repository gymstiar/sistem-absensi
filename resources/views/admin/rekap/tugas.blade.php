<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Tugas - Sistem Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
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
        .export-btn {
            margin-right: 5px;
        }
        .table-responsive {
            margin-top: 20px;
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
                            <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-chart-bar"></i> Rekap Data
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/admin/rekap/absensi">Rekap Absensi</a></li>
                                <li><a class="dropdown-item active" href="/admin/rekap/tugas">Rekap Tugas</a></li>
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
                    <h1 class="h2">Rekap Tugas</h1>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download"></i> Ekspor Laporan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" id="export-pdf"><i class="fas fa-file-pdf"></i> PDF</a></li>
                            <li><a class="dropdown-item" href="#" id="export-csv"><i class="fas fa-file-csv"></i> CSV</a></li>
                            <li><a class="dropdown-item" href="#" id="export-excel"><i class="fas fa-file-excel"></i> Excel</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h4>{{ $rekapTugas['total_tugas'] }}</h4>
                                <p>Total Tugas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h4>{{ $rekapTugas['tugas_terkumpul'] }}</h4>
                                <p>Tugas Terkumpul</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <h4>{{ number_format($rekapTugas['rata_nilai'], 2) }}</h4>
                                <p>Rata-rata Nilai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tugas-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Tugas</th>
                                <th>Mentor</th>
                                <th>Kelas</th>
                                <th>Tanggal Upload</th>
                                <th>Batas Waktu</th>
                                <th>Jml Pengumpulan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tugas as $index => $tugasItem)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tugasItem->judul_tugas }}</td>
                                <td>{{ $tugasItem->mentor->nama }}</td>
                                <td>{{ $tugasItem->kelas->nama_kelas }}</td>
                                <td>{{ $tugasItem->tanggal_upload }}</td>
                                <td>{{ $tugasItem->batas_waktu }}</td>
                                <td>{{ $tugasItem->pengumpulan->count() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ekspor ke PDF
            document.getElementById('export-pdf').addEventListener('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Judul laporan
                doc.setFontSize(16);
                doc.text('Rekap Tugas - Sistem Absensi', 14, 15);
                
                // Statistik
                doc.setFontSize(10);
                doc.text(`Total Tugas: ${document.querySelector('.bg-primary h4').textContent}`, 14, 25);
                doc.text(`Tugas Terkumpul: ${document.querySelector('.bg-success h4').textContent}`, 14, 32);
                doc.text(`Rata-rata Nilai: ${document.querySelector('.bg-info h4').textContent}`, 14, 39);
                
                // Tabel
                doc.autoTable({
                    html: '#tugas-table',
                    startY: 45,
                    styles: { fontSize: 8 },
                    headStyles: { fillColor: [52, 58, 64] }
                });
                
                // Simpan PDF
                doc.save('rekap-tugas.pdf');
            });
            
            // Ekspor ke CSV
            document.getElementById('export-csv').addEventListener('click', function() {
                let csv = [];
                let rows = document.querySelectorAll("#tugas-table tr");
                
                for (let i = 0; i < rows.length; i++) {
                    let row = [], cols = rows[i].querySelectorAll("td, th");
                    
                    for (let j = 0; j < cols.length; j++) {
                        row.push(cols[j].innerText);
                    }
                    
                    csv.push(row.join(","));        
                }
                
                // Download CSV
                downloadCSV(csv.join("\n"), 'rekap-tugas.csv');
            });
            
            // Ekspor ke Excel (format CSV dengan ekstensi .xls untuk kompatibilitas)
            document.getElementById('export-excel').addEventListener('click', function() {
                let csv = [];
                let rows = document.querySelectorAll("#tugas-table tr");
                
                for (let i = 0; i < rows.length; i++) {
                    let row = [], cols = rows[i].querySelectorAll("td, th");
                    
                    for (let j = 0; j < cols.length; j++) {
                        row.push(cols[j].innerText);
                    }
                    
                    csv.push(row.join("\t")); // Gunakan tab sebagai pemisah untuk Excel
                }
                
                // Download Excel (CSV dengan tab sebagai pemisah)
                downloadCSV(csv.join("\n"), 'rekap-tugas.xls');
            });
            
            // Fungsi untuk mengunduh file CSV
            function downloadCSV(csv, filename) {
                let csvFile;
                let downloadLink;
                
                // Membuat file CSV
                csvFile = new Blob(["\uFEFF" + csv], {type: 'text/csv;charset=utf-8;'});
                
                // Membuat link download
                downloadLink = document.createElement("a");
                
                // Membuat nama file
                downloadLink.download = filename;
                
                // Membuat link ke file
                downloadLink.href = window.URL.createObjectURL(csvFile);
                
                // Menyembunyikan link download
                downloadLink.style.display = "none";
                
                // Menambahkan link ke DOM
                document.body.appendChild(downloadLink);
                
                // Klik link
                downloadLink.click();
                
                // Hapus link dari DOM
                document.body.removeChild(downloadLink);
            }
        });
    </script>
</body>
</html>