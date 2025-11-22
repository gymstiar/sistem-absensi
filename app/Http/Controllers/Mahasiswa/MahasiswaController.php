<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    // Absensi
    public function absensiIndex()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $absensi = Absensi::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->with('mentor')
            ->latest()
            ->get();
            
        return view('mahasiswa.absensi.index', compact('absensi'));
    }

    public function absensiCreate()
    {
        return view('mahasiswa.absensi.create');
    }

    public function absensiStore(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
            'foto_bukti' => 'required_if:status,hadir|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        $filePath = null;
        if ($request->hasFile('foto_bukti')) {
            $filePath = $request->file('foto_bukti')->store('absensi', 'public');
        }

        // Cari mentor berdasarkan kelas mahasiswa
        $kelas = Kelas::where('nama_kelas', $mahasiswa->kelas)->first();
        
        if (!$kelas) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        Absensi::create([
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'id_mentor' => $kelas->id_mentor,
            'tanggal' => now(),
            'status' => $request->status,
            'foto_bukti' => $filePath,
            'waktu_input' => now(),
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/mahasiswa/absensi')->with('success', 'Absensi berhasil dicatat.');
    }

    // Tugas
    // Di file app/Http/Controllers/Mahasiswa/MahasiswaController.php

    public function tugasIndex()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        $tugas = Tugas::whereHas('kelas', function($query) use ($mahasiswa) {
            $query->where('nama_kelas', $mahasiswa->kelas);
        })
        ->with(['mentor', 'kelas', 'pengumpulan' => function($query) use ($mahasiswa) {
            $query->where('id_mahasiswa', $mahasiswa->id_mahasiswa);
        }])
        ->get()
        ->map(function($tugas) use ($mahasiswa) {
            // Tambahkan flag untuk menandai apakah sudah dikumpulkan
            $tugas->sudah_dikumpulkan = $tugas->pengumpulan->count() > 0;
            return $tugas;
        });

        return view('mahasiswa.tugas.index', compact('tugas'));
    }

    public function kumpulkanTugas(Request $request, $id)
    {
        $request->validate([
            'file_dikumpulkan' => 'required|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:2048',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;
        $tugas = Tugas::findOrFail($id);

        $filePath = $request->file('file_dikumpulkan')->store('pengumpulan-tugas', 'public');

        PengumpulanTugas::create([
            'id_tugas' => $id,
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'file_dikumpulkan' => $filePath,
            'tanggal_kumpul' => now(),
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function nilaiIndex()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        $nilai = PengumpulanTugas::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->with('tugas')
            ->whereNotNull('nilai')
            ->get();

        return view('mahasiswa.nilai.index', compact('nilai'));
    }
}