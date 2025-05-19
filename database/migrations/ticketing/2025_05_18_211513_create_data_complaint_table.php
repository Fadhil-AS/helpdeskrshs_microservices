<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('DATA_COMPLAINT', function (Blueprint $table) {
            $table->string('ID_COMPLAINT', 20)->primary();
            $table->string('ID_BAGIAN', 5);
            $table->string('ID_KLASIFIKASI', 20);
            $table->string('ID_JENIS_MEDIA', 20);
            $table->string('ID_PENYELESAIAN', 20);
            $table->string('ID_JENIS_LAPORAN', 20);
            $table->date('TGL_COMPLAINT');
            $table->timestamp('TGL_INSROW');
            $table->string('STATUS', 30);
            $table->string('EVALUASI_COMPLAINT', 4000);
            $table->string('JUDUL_COMPLAINT', 4000);
            $table->date('TGL_EVALUASI');
            $table->string('GRANDING', 30);
            $table->string('PETUGAS_PELAPOR', 100);
            $table->string('KETERANGAN', 50);
            $table->string('NO_MEDREC', 10);
            $table->string('PENANGGUNG_JAWAB', 70);
            $table->date('TGL_SELESAI');
            $table->string('DATA_PENGADUAN', 300);
            $table->string('SMS_DIREKSI', 50);
            $table->string('FILE_PENGADUAN', 100);
            $table->text('TINDAK_LANJUT_HUMAS', 4000);
            $table->string('DISPOSISI', 100);
            $table->string('INFO_DIREKSI', 200);
            $table->text('PERMASALAHAN', 4000);
            $table->string('KD_PENGADUAN', 50);
            $table->timestamps();

            $table->foreign('ID_BAGIAN')->references('ID_BAGIAN')->on('UNIT_KERJA');
            $table->foreign('ID_KLASIFIKASI')->references('ID_KLASIFIKASI')->on('KLASIFIKASI_PENGADUAN');
            $table->foreign('ID_JENIS_MEDIA')->references('ID_JENIS_MEDIA')->on('JENIS_MEDIA');
            $table->foreign('ID_JENIS_LAPORAN')->references('ID_JENIS_LAPORAN')->on('JENIS_LAPORAN');
            $table->foreign('ID_PENYELESAIAN')->references('ID_PENYELESAIAN')->on('PENYELESAIAN_PENGADUAN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DATA_COMPLAINT');
    }
};
