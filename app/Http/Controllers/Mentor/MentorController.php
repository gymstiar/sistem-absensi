<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\PengumpulanTugas;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    // Tugas Management
    public function tugasIndex()
    {
        $mentor = auth()->user()->mentor;
        
        $tugas = Tugas::where('id_mentor', $mentor->id_mentor)
            ->with(['kelas', 'pengumpulan'])
            ->get()
            ->map(function($tugas) {
                $tugas->pengumpulan_count = $tugas->pengumpulan->count();
                return $tugas;
            });
            
        $kelas = Kelas::where('id_mentor', $mentor->id_mentor)->get();
        $totalPengumpulan = PengumpulanTugas::whereHas('tugas', function($query) use ($mentor) {
            $query->where('id_mentor', $mentor->id_mentor);
        })->count();

        return view('mentor.tugas.index', compact('tugas', 'kelas', 'totalPengumpulan'));
    }

    public function tugasCreate()
    {
        $mentor = auth()->user()->mentor;
        $kelas = Kelas::where('id_mentor', $mentor->id_mentor)->get();
        return view('mentor.tugas.create', compact('kelas'));
    }

    public function tugasStore(Request $request)
    {
        $request->validate([
            'judul_tugas' => 'required',
            'deskripsi' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'batas_waktu' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,zip|max:2048',
        ]);

        $mentor = auth()->user()->mentor;

        $filePath = null;
        if ($request->hasFile('file_tugas')) {
            $filePath = $request->file('file_tugas')->store('tugas', 'public');
        }

        Tugas::create([
            'id_mentor' => $mentor->id_mentor,
            'id_kelas' => $request->id_kelas,
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'file_tugas' => $filePath,
            'tanggal_upload' => now(),
            'batas_waktu' => $request->batas_waktu,
        ]);

        return redirect('/mentor/tugas')->with('success', 'Tugas berhasil dibuat.');
    }

    public function tugasUpdate(Request $request, $id)
    {
        $request->validate([
            'judul_tugas' => 'required',
            'deskripsi' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'batas_waktu' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,zip|max:2048',
        ]);

        $tugas = Tugas::findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit tugas ini.');
        }

        $filePath = $tugas->file_tugas;
        if ($request->hasFile('file_tugas')) {
            // Hapus file lama jika ada
            if ($tugas->file_tugas) {
                Storage::delete($tugas->file_tugas);
            }
            $filePath = $request->file('file_tugas')->store('tugas', 'public');
        }

        $tugas->update([
            'id_kelas' => $request->id_kelas,
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'file_tugas' => $filePath,
            'batas_waktu' => $request->batas_waktu,
        ]);

        return redirect('/mentor/tugas')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function tugasDelete($id)
    {
        $tugas = Tugas::findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus tugas ini.');
        }

        // Hapus file jika ada
        if ($tugas->file_tugas) {
            Storage::delete($tugas->file_tugas);
        }

        $tugas->delete();

        return redirect('/mentor/tugas')->with('success', 'Tugas berhasil dihapus.');
    }

    // Absensi Management
    public function absensiIndex()
    {
        $mentor = auth()->user()->mentor;
        $absensi = Absensi::where('id_mentor', $mentor->id_mentor)
            ->with(['mahasiswa', 'mahasiswa.user'])
            ->latest()
            ->get();
            
        return view('mentor.absensi.index', compact('absensi'));
    }

    public function verifikasiAbsensi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
            'keterangan' => 'nullable',
        ]);

        $absensi = Absensi::findOrFail($id);
        
        // Cek apakah absensi milik mentor yang login
        if ($absensi->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk memverifikasi absensi ini.');
        }

        $absensi->update([
            'status_verifikasi' => $request->status_verifikasi,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    // Penilaian Tugas
    public function penilaianIndex(Request $request)
    {
    $mentor = auth()->user()->mentor;
    
    $query = PengumpulanTugas::whereHas('tugas', function($query) use ($mentor) {
        $query->where('id_mentor', $mentor->id_mentor);
    })->with(['tugas', 'tugas.kelas', 'mahasiswa', 'mahasiswa.user']);

    // Filter by tugas jika ada parameter
    if ($request->has('tugas') && $request->tugas) {
        $query->where('id_tugas', $request->tugas);
    }

    $pengumpulan = $query->get();
    
    // Ambil daftar tugas untuk filter
    $tugasList = Tugas::where('id_mentor', $mentor->id_mentor)
        ->with('kelas')
        ->get();

    return view('mentor.penilaian.index', compact('pengumpulan', 'tugasList'));
    }

    public function beriNilai(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan_mentor' => 'nullable',
        ]);

        $pengumpulan = PengumpulanTugas::findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($pengumpulan->tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk memberikan nilai pada tugas ini.');
        }

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'catatan_mentor' => $request->catatan_mentor,
        ]);

        return back()->with('success', 'Nilai berhasil diberikan.');
    }

    // Dashboard Statistik
    public function getDashboardStats()
    {
        $mentor = auth()->user()->mentor;
        
        $stats = [
            'total_tugas' => Tugas::where('id_mentor', $mentor->id_mentor)->count(),
            'total_kelas' => Kelas::where('id_mentor', $mentor->id_mentor)->count(),
            'absensi_hari_ini' => Absensi::where('id_mentor', $mentor->id_mentor)
                ->whereDate('tanggal', today())
                ->count(),
            'tugas_perlu_dinilai' => PengumpulanTugas::whereHas('tugas', function($query) use ($mentor) {
                $query->where('id_mentor', $mentor->id_mentor);
            })->whereNull('nilai')->count(),
        ];

        return $stats;
    }


    public function dashboard()
    {
    $mentor = auth()->user()->mentor;
    
    $totalTugas = Tugas::where('id_mentor', $mentor->id_mentor)->count();
    $totalKelas = Kelas::where('id_mentor', $mentor->id_mentor)->count();
    
    $absensiHariIni = Absensi::where('id_mentor', $mentor->id_mentor)
        ->whereDate('tanggal', today())
        ->count();
        
    $tugasPerluDinilai = PengumpulanTugas::whereHas('tugas', function($query) use ($mentor) {
        $query->where('id_mentor', $mentor->id_mentor);
    })->whereNull('nilai')->count();

    // Data tambahan untuk dashboard yang lebih kaya
    $tugasAktif = Tugas::where('id_mentor', $mentor->id_mentor)
        ->where('batas_waktu', '>=', now())
        ->count();
        
    $hadirHariIni = Absensi::where('id_mentor', $mentor->id_mentor)
        ->whereDate('tanggal', today())
        ->where('status', 'hadir')
        ->where('status_verifikasi', 'diterima')
        ->count();
        
    $totalMahasiswa = Mahasiswa::whereIn('kelas', function($query) use ($mentor) {
        $query->select('nama_kelas')
              ->from('kelas')
              ->where('id_mentor', $mentor->id_mentor);
    })->count();
    
    $totalPengumpulan = PengumpulanTugas::whereHas('tugas', function($query) use ($mentor) {
        $query->where('id_mentor', $mentor->id_mentor);
    })->count();

    return view('mentor.dashboard', compact(
        'totalTugas',
        'totalKelas',
        'absensiHariIni',
        'tugasPerluDinilai',
        'tugasAktif',
        'hadirHariIni',
        'totalMahasiswa',
        'totalPengumpulan'
    ));
}


    public function tugasJson($id)
    {
        $tugas = Tugas::with('kelas')->findOrFail($id);

        return response()->json($tugas);
    }


    // Get Tugas by Kelas untuk Filter
    public function getTugasByKelas($kelasId)
    {
        $mentor = auth()->user()->mentor;
        
        $tugas = Tugas::where('id_mentor', $mentor->id_mentor)
            ->where('id_kelas', $kelasId)
            ->withCount('pengumpulan')
            ->get();

        return response()->json($tugas);
    }

    // Download File Tugas
    public function downloadFileTugas($id)
    {
        $tugas = Tugas::findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mendownload file ini.');
        }

        if (!$tugas->file_tugas) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($tugas->file_tugas);
    }

    // Download File Pengumpulan
    public function downloadFilePengumpulan($id)
    {
        $pengumpulan = PengumpulanTugas::findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($pengumpulan->tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mendownload file ini.');
        }

        if (!$pengumpulan->file_dikumpulkan) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($pengumpulan->file_dikumpulkan);
    }

    // Lihat Detail Pengumpulan
    public function detailPengumpulan($id)
    {
        $pengumpulan = PengumpulanTugas::with(['tugas', 'mahasiswa', 'mahasiswa.user'])
            ->findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($pengumpulan->tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk melihat detail ini.');
        }

        return view('mentor.penilaian.detail', compact('pengumpulan'));
    }

    // Statistik Tugas
    public function statistikTugas()
    {
        $mentor = auth()->user()->mentor;
        
        $tugas = Tugas::where('id_mentor', $mentor->id_mentor)
            ->withCount(['pengumpulan', 'pengumpulan as sudah_dinilai_count' => function($query) {
                $query->whereNotNull('nilai');
            }])
            ->with('kelas')
            ->get();

        $statistik = [
            'total_tugas' => $tugas->count(),
            'total_pengumpulan' => $tugas->sum('pengumpulan_count'),
            'rata_rata_pengumpulan' => $tugas->avg('pengumpulan_count'),
            'tugas_aktif' => $tugas->where('batas_waktu', '>=', now())->count(),
            'tugas_selesai' => $tugas->where('batas_waktu', '<', now())->count(),
        ];

        return view('mentor.tugas.statistik', compact('tugas', 'statistik'));
    }

    // Export Data (Simple)
    public function exportTugas($id)
    {
        $tugas = Tugas::with(['pengumpulan', 'pengumpulan.mahasiswa'])
            ->findOrFail($id);
        
        // Cek apakah tugas milik mentor yang login
        if ($tugas->id_mentor != auth()->user()->mentor->id_mentor) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengekspor data ini.');
        }

        $data = [
            'tugas' => $tugas,
            'pengumpulan' => $tugas->pengumpulan
        ];

        // Untuk sementara return view, nanti bisa dikembangkan ke PDF/Excel
        return view('mentor.tugas.export', $data);
    }
}