<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengumpulan';
    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'id_tugas',
        'id_mahasiswa',
        'file_dikumpulkan',
        'tanggal_kumpul',
        'nilai',
        'catatan_mentor',
    ];

    protected $casts = [
        'tanggal_kumpul' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}