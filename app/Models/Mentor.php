<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mentor';
    protected $table = 'mentors';

    protected $fillable = [
        'id_user',
        'nama',
        'mata_kuliah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_mentor', 'id_mentor');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_mentor', 'id_mentor');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_mentor', 'id_mentor');
    }
}