<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tugas';
    protected $table = 'tugas';

    protected $fillable = [
        'id_mentor',
        'id_kelas',
        'judul_tugas',
        'deskripsi',
        'file_tugas',
        'tanggal_upload',
        'batas_waktu',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
        'batas_waktu' => 'datetime',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor', 'id_mentor');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_tugas', 'id_tugas');
    }
}