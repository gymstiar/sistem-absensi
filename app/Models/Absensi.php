<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_absen';
    protected $table = 'absensis';

    protected $fillable = [
        'id_mahasiswa',
        'id_mentor',
        'tanggal',
        'status',
        'foto_bukti',
        'waktu_input',
        'status_verifikasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_input' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor', 'id_mentor');
    }
}