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
        Schema::create('KLASIFIKASI_PENGADUAN', function (Blueprint $table) {
            $table->string('ID_KLASIFIKASI', 20)->primary();
            $table->string('KLASIFIKASI_PENGADUAN', 200);
            $table->char('STATUS', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('KLASIFIKASI_PENGADUAN');
    }
};
