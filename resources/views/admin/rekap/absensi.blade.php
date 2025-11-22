<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi - Sistem Absensi</title>
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
        .badge {
            font-size: 0.85em;
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
                                <li><a class="dropdown-item active" href="/admin/rekap/absensi">Rekap Absensi</a></li>
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
                    <h1 class="h2">Rekap Absensi</h1>
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
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h4>{{ $rekapStatus['hadir'] }}</h4>
                                <p>Hadir</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h4>{{ $rekapStatus['izin'] }}</h4>
                                <p>Izin</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body text-center">
                                <h4>{{ $rekapStatus['sakit'] }}</h4>
                                <p>Sakit</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <h4>{{ array_sum($rekapStatus) }}</h4>
                                <p>Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="absensi-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>Mentor</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi as $index => $absen)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $absen->mahasiswa->nama }}</td>
                                <td>{{ $absen->mentor->nama }}</td>
                                <td>
                                    @php
                                        // Format tanggal tanpa waktu 00:00:00
                                        $tanggal = \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y');
                                    @endphp
                                    {{ $tanggal }}
                                </td>
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
                                        @else bg-secondary @endif">
                                        {{ $absen->status_verifikasi }}
                                    </span>
                                </td>
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
            // Fungsi untuk format tanggal Indonesia
            function formatTanggalIndonesia(date) {
                const bulan = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                
                const d = new Date(date);
                const hariIni = hari[d.getDay()];
                const tanggal = d.getDate();
                const bulanIni = bulan[d.getMonth()];
                const tahun = d.getFullYear();
                const jam = d.getHours().toString().padStart(2, '0');
                const menit = d.getMinutes().toString().padStart(2, '0');
                const detik = d.getSeconds().toString().padStart(2, '0');
                
                return `${hariIni}, ${tanggal} ${bulanIni} ${tahun} ${jam}:${menit}:${detik}`;
            }

            // Fungsi untuk format tanggal sederhana (DD-MM-YYYY)
            function formatTanggalSederhana(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString; // Jika parsing gagal, kembalikan string asli
                
                const tanggal = date.getDate().toString().padStart(2, '0');
                const bulan = (date.getMonth() + 1).toString().padStart(2, '0');
                const tahun = date.getFullYear();
                
                return `${tanggal}-${bulan}-${tahun}`;
            }

            // Ekspor ke PDF
            document.getElementById('export-pdf').addEventListener('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                const sekarang = new Date();
                const tanggalDownload = formatTanggalIndonesia(sekarang);
                
                // Judul laporan
                doc.setFontSize(16);
                doc.text('REKAP ABSENSI - SISTEM ABSENSI', 14, 15);
                
                // Informasi download
                doc.setFontSize(8);
                doc.setTextColor(100, 100, 100);
                doc.text(`Dicetak pada: ${tanggalDownload}`, 14, 22);
                doc.setTextColor(0, 0, 0);
                
                // Statistik
                doc.setFontSize(10);
                doc.text(`Hadir: ${document.querySelector('.bg-success h4').textContent}`, 14, 32);
                doc.text(`Izin: ${document.querySelector('.bg-warning h4').textContent}`, 14, 39);
                doc.text(`Sakit: ${document.querySelector('.bg-danger h4').textContent}`, 14, 46);
                doc.text(`Total: ${document.querySelector('.bg-info h4').textContent}`, 14, 53);
                
                // Persiapan data untuk tabel
                const table = document.getElementById('absensi-table');
                const headers = [];
                const rows = [];
                
                // Ambil header
                table.querySelectorAll('thead th').forEach(th => {
                    headers.push(th.textContent.trim());
                });
                
                // Ambil data rows
                table.querySelectorAll('tbody tr').forEach(tr => {
                    const row = [];
                    tr.querySelectorAll('td').forEach((td, index) => {
                        let text = td.textContent.trim();
                        // Format kolom tanggal (index 3)
                        if (index === 3) {
                            text = formatTanggalSederhana(text);
                        }
                        row.push(text);
                    });
                    rows.push(row);
                });
                
                // Buat tabel
                doc.autoTable({
                    head: [headers],
                    body: rows,
                    startY: 60,
                    styles: { 
                        fontSize: 8,
                        cellPadding: 2
                    },
                    headStyles: { 
                        fillColor: [52, 58, 64],
                        textColor: 255,
                        fontStyle: 'bold'
                    },
                    columnStyles: {
                        0: { cellWidth: 10 }, // No
                        1: { cellWidth: 40 }, // Mahasiswa
                        2: { cellWidth: 40 }, // Mentor
                        3: { cellWidth: 25 }, // Tanggal
                        4: { cellWidth: 20 }, // Status
                        5: { cellWidth: 25 }  // Verifikasi
                    },
                    didDrawCell: function(data) {
                        // Menangani sel dengan badge (kolom Status dan Verifikasi)
                        if (data.section === 'body' && (data.column.index === 4 || data.column.index === 5)) {
                            const status = data.cell.text[0];
                            
                            // Set warna berdasarkan status
                            let color;
                            if (data.column.index === 4) { // Kolom Status
                                if (status === 'hadir') color = [40, 167, 69];
                                else if (status === 'izin') color = [255, 193, 7];
                                else color = [220, 53, 69];
                            } else { // Kolom Verifikasi
                                if (status === 'diterima') color = [40, 167, 69];
                                else if (status === 'ditolak') color = [220, 53, 69];
                                else color = [108, 117, 125];
                            }
                            
                            // Tambahkan background color
                            doc.setFillColor(...color);
                            doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                            
                            // Tambahkan teks dengan warna putih
                            doc.setTextColor(255, 255, 255);
                            doc.text(status, data.cell.x + 2, data.cell.y + data.cell.height / 2 + 1);
                            doc.setTextColor(0, 0, 0);
                            
                            return false; // Mencegah rendering default
                        }
                    }
                });
                
                // Footer dengan informasi halaman
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    doc.text(`Halaman ${i} dari ${pageCount}`, doc.internal.pageSize.width - 30, doc.internal.pageSize.height - 10);
                }
                
                // Simpan PDF
                doc.save(`rekap-absensi-${sekarang.getTime()}.pdf`);
            });
            
            // Ekspor ke CSV
            document.getElementById('export-csv').addEventListener('click', function() {
                let csv = [];
                let rows = document.querySelectorAll("#absensi-table tr");
                
                for (let i = 0; i < rows.length; i++) {
                    let row = [], cols = rows[i].querySelectorAll("td, th");
                    
                    for (let j = 0; j < cols.length; j++) {
                        let text = cols[j].innerText.trim();
                        // Format kolom tanggal (index 3)
                        if (j === 3 && i > 0) { // Skip header
                            text = formatTanggalSederhana(text);
                        }
                        row.push(text);
                    }
                    
                    csv.push(row.join(","));        
                }
                
                // Download CSV
                downloadCSV(csv.join("\n"), `rekap-absensi-${new Date().getTime()}.csv`);
            });
            
            // Ekspor ke Excel (format CSV dengan ekstensi .xls untuk kompatibilitas)
            document.getElementById('export-excel').addEventListener('click', function() {
                let csv = [];
                let rows = document.querySelectorAll("#absensi-table tr");
                
                for (let i = 0; i < rows.length; i++) {
                    let row = [], cols = rows[i].querySelectorAll("td, th");
                    
                    for (let j = 0; j < cols.length; j++) {
                        let text = cols[j].innerText.trim();
                        // Format kolom tanggal (index 3)
                        if (j === 3 && i > 0) { // Skip header
                            text = formatTanggalSederhana(text);
                        }
                        row.push(text);
                    }
                    
                    csv.push(row.join("\t")); // Gunakan tab sebagai pemisah untuk Excel
                }
                
                // Download Excel (CSV dengan tab sebagai pemisah)
                downloadCSV(csv.join("\n"), `rekap-absensi-${new Date().getTime()}.xls`);
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