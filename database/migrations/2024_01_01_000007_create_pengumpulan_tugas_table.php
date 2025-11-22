<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->foreignId('id_tugas')->constrained('tugas', 'id_tugas');
            $table->foreignId('id_mahasiswa')->constrained('mahasiswas', 'id_mahasiswa');
            $table->string('file_dikumpulkan');
            $table->timestamp('tanggal_kumpul');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan_mentor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};