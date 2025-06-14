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
            $table->string('ID_COMPLAINT_REFERENSI')->nullable();
            $table->string('ID_BAGIAN', 5)->nullable();
            $table->string('ID_KLASIFIKASI', 20)->nullable();
            $table->string('ID_JENIS_MEDIA', 20)->nullable();
            $table->string('ID_PENYELESAIAN', 20)->nullable();
            $table->string('ID_JENIS_LAPORAN', 20)->nullable();
            $table->date('TGL_COMPLAINT');
            $table->string('NAME', 70)->nullable();
            $table->string('NO_TLPN', 100)->nullable();
            $table->text('ISI_COMPLAINT', 4000)->nullable();
            $table->date('TGL_INSROW');
            $table->string('STATUS', 30);
            $table->string('EVALUASI_COMPLAINT', 4000)->nullable();
            $table->string('JUDUL_COMPLAINT', 4000)->nullable();
            $table->string('PETUGAS_EVALUASI', 100)->nullable();
            $table->date('TGL_EVALUASI')->nullable();
            $table->string('GRANDING', 30)->nullable();
            $table->string('PETUGAS_PELAPOR', 100)->nullable();
            $table->string('NO_MEDREC', 10)->nullable();
            // $table->string('PENANGGUNG_JAWAB', 70)->nullable();
            $table->date('TGL_SELESAI')->nullable();
            // $table->string('DATA_PENGADUAN', 300)->nullable();
            $table->string('SMS_DIREKSI', 50)->nullable();
            $table->string('FILE_PENGADUAN', 100)->nullable();
            $table->text('TINDAK_LANJUT_HUMAS', 4000)->nullable();
            $table->string('DISPOSISI', 100)->nullable();
            // $table->string('INFO_DIREKSI', 200)->nullable();
            $table->text('PERMASALAHAN', 4000)->nullable();
            // $table->string('KD_PENGADUAN', 50)->nullable();
            $table->string('RATING_LAPORAN', 255)->nullable();
            $table->text('FEEDBACK_PELAPOR', 4000)->nullable();
            // $table->timestamps();

            $table->foreign('ID_COMPLAINT_REFERENSI')->references('ID_COMPLAINT')->on('DATA_COMPLAINT')->onDelete('set null');
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
