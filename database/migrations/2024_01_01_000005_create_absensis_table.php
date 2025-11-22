<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id('id_absen');
            $table->foreignId('id_mahasiswa')->constrained('mahasiswas', 'id_mahasiswa');
            $table->foreignId('id_mentor')->constrained('mentors', 'id_mentor');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit']);
            $table->string('foto_bukti')->nullable();
            $table->timestamp('waktu_input');
            $table->enum('status_verifikasi', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};