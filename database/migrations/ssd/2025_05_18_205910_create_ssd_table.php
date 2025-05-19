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
        Schema::connection('ssd')->create('KATEGORI_SSD', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_KATEGORI_SSD')->primary();
            $table->string('DESKRIPSI', 255);
            $table->string('NAMA_KATEGORI', 255);
            $table->timestamps();
        });

        Schema::connection('ssd')->create('SSD', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_SSD')->primary();
            $table->unsignedBigInteger('ID_KATEGORI_SSD');
            $table->foreign('ID_KATEGORI_SSD')->references('ID_KATEGORI_SSD')->on('KATEGORI_SSD');
            $table->string('PERTANYAAN_SSD', 255);
            $table->string('JAWABAN_SSD', 255);
            $table->char('STATUS', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ssd')->dropIfExists('SSD');
        Schema::connection('ssd')->dropIfExists('KATEGORI_SSD');
    }
};
