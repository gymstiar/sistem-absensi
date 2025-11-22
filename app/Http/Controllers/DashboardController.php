<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Kelas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalMentor = Mentor::count();
        $totalKelas = Kelas::count();
        
        $absensiHariIni = Absensi::whereDate('tanggal', today())->count();
        $tugasAktif = Tugas::where('batas_waktu', '>=', now())->count();
        
        $pengumpulan7Hari = PengumpulanTugas::where('tanggal_kumpul', '>=', now()->subDays(7))->count();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalMentor',
            'totalKelas',
            'absensiHariIni',
            'tugasAktif',
            'pengumpulan7Hari'
        ));
    }

    public function mentorDashboard()
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

        return view('mentor.dashboard', compact(
            'totalTugas',
            'totalKelas',
            'absensiHariIni',
            'tugasPerluDinilai'
        ));
    }

    public function mahasiswaDashboard()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        $absensiBulanIni = Absensi::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->whereMonth('tanggal', now()->month)
            ->count();
            
        $tugasAktif = Tugas::whereHas('kelas', function($query) use ($mahasiswa) {
            $query->where('nama_kelas', $mahasiswa->kelas);
        })->where('batas_waktu', '>=', now())->count();
        
        $tugasTerkumpul = PengumpulanTugas::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->count();

        return view('mahasiswa.dashboard', compact(
            'absensiBulanIni',
            'tugasAktif',
            'tugasTerkumpul'
        ));
    }
}