<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Kelas;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $adminUser = User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Mentor
        $mentorUser = User::create([
            'username' => 'mentor',
            'password' => Hash::make('password'),
            'role' => 'mentor',
        ]);

        $mentor = Mentor::create([
            'id_user' => $mentorUser->id_user,
            'nama' => 'Dr. Ahmad Wijaya, M.Kom',
            'mata_kuliah' => 'Pemrograman Web',
        ]);

        // Create Kelas
        $kelas = Kelas::create([
            'nama_kelas' => 'TI-1A',
            'id_mentor' => $mentor->id_mentor,
        ]);

        // Create Mahasiswa 1
        $mahasiswa1User = User::create([
            'username' => 'mahasiswa1',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'id_user' => $mahasiswa1User->id_user,
            'nim' => '20210001',
            'nama' => 'Budi Santoso',
            'kelas' => 'TI-1A',
        ]);

        // Create Mahasiswa 2
        $mahasiswa2User = User::create([
            'username' => 'mahasiswa2',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'id_user' => $mahasiswa2User->id_user,
            'nim' => '20210002',
            'nama' => 'Siti Rahayu',
            'kelas' => 'TI-1A',
        ]);

        $this->command->info('Data sample berhasil dibuat!');
        $this->command->info('Admin: username=admin, password=password');
        $this->command->info('Mentor: username=mentor, password=password');
        $this->command->info('Mahasiswa 1: username=mahasiswa1, password=password');
        $this->command->info('Mahasiswa 2: username=mahasiswa2, password=password');
    }
}