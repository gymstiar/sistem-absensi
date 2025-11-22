<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kelas';
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'id_mentor',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor', 'id_mentor');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_kelas', 'id_kelas');
    }
}