<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Mahasiswa Management
    public function mahasiswaIndex()
    {
        $mahasiswas = Mahasiswa::with('user')->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function mahasiswaCreate()
    {
        return view('admin.mahasiswa.create');
    }

    public function mahasiswaStore(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'nim' => 'required|unique:mahasiswas',
            'nama' => 'required',
            'kelas' => 'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'id_user' => $user->id_user,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
        ]);

        return redirect('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    // Mentor Management
    public function mentorIndex()
    {
        $mentors = Mentor::with('user')->get();
        return view('admin.mentor.index', compact('mentors'));
    }

    public function mentorCreate()
    {
        return view('admin.mentor.create');
    }

    public function mentorStore(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'nama' => 'required',
            'mata_kuliah' => 'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'mentor',
        ]);

        Mentor::create([
            'id_user' => $user->id_user,
            'nama' => $request->nama,
            'mata_kuliah' => $request->mata_kuliah,
        ]);

        return redirect('/admin/mentor')->with('success', 'Mentor berhasil ditambahkan.');
    }

    // Kelas Management
    public function kelasIndex()
    {
        $kelas = Kelas::with('mentor')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function kelasCreate()
    {
        $mentors = Mentor::all();
        return view('admin.kelas.create', compact('mentors'));
    }

    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'id_mentor' => 'required|exists:mentors,id_mentor',
        ]);

        Kelas::create($request->all());

        return redirect('/admin/kelas')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Rekap Data
    public function rekapAbsensi(Request $request)
    {
    $query = Absensi::with(['mahasiswa', 'mentor']);

    // Filter by date range
    if ($request->has('start_date') && $request->start_date) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }
    
    if ($request->has('end_date') && $request->end_date) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }

    // Filter by status
    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status);
    }

    // Filter by verification status
    if ($request->has('verifikasi') && $request->verifikasi) {
        $query->where('status_verifikasi', $request->verifikasi);
    }

    $absensi = $query->latest()->get();
    
    $rekapStatus = [
        'hadir' => Absensi::where('status', 'hadir')->when($request->start_date, function($q) use ($request) {
            $q->whereDate('tanggal', '>=', $request->start_date);
        })->when($request->end_date, function($q) use ($request) {
            $q->whereDate('tanggal', '<=', $request->end_date);
        })->when($request->status, function($q) use ($request) {
            $q->where('status', $request->status);
        })->when($request->verifikasi, function($q) use ($request) {
            $q->where('status_verifikasi', $request->verifikasi);
        })->count(),
        
        'izin' => Absensi::where('status', 'izin')->when($request->start_date, function($q) use ($request) {
            $q->whereDate('tanggal', '>=', $request->start_date);
        })->when($request->end_date, function($q) use ($request) {
            $q->whereDate('tanggal', '<=', $request->end_date);
        })->when($request->status, function($q) use ($request) {
            $q->where('status', $request->status);
        })->when($request->verifikasi, function($q) use ($request) {
            $q->where('status_verifikasi', $request->verifikasi);
        })->count(),
        
        'sakit' => Absensi::where('status', 'sakit')->when($request->start_date, function($q) use ($request) {
            $q->whereDate('tanggal', '>=', $request->start_date);
        })->when($request->end_date, function($q) use ($request) {
            $q->whereDate('tanggal', '<=', $request->end_date);
        })->when($request->status, function($q) use ($request) {
            $q->where('status', $request->status);
        })->when($request->verifikasi, function($q) use ($request) {
            $q->where('status_verifikasi', $request->verifikasi);
        })->count(),
    ];

    return view('admin.rekap.absensi', compact('absensi', 'rekapStatus'));
    }

    public function rekapTugas()
    {
        $tugas = Tugas::with(['mentor', 'kelas', 'pengumpulan'])->get();
        
        $rekapTugas = [
            'total_tugas' => Tugas::count(),
            'tugas_terkumpul' => PengumpulanTugas::distinct('id_tugas')->count('id_tugas'),
            'rata_nilai' => PengumpulanTugas::whereNotNull('nilai')->avg('nilai'),
        ];

        return view('admin.rekap.tugas', compact('tugas', 'rekapTugas'));
    }
}