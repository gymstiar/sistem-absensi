<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mahasiswa';
    protected $table = 'mahasiswas';

    protected $fillable = [
        'id_user',
        'nim',
        'nama',
        'kelas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}