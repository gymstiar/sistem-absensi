<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->foreignId('id_mentor')->constrained('mentors', 'id_mentor');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas');
            $table->string('judul_tugas');
            $table->text('deskripsi');
            $table->string('file_tugas')->nullable();
            $table->date('tanggal_upload');
            $table->dateTime('batas_waktu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};