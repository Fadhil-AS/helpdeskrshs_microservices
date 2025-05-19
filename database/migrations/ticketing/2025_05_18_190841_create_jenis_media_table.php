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
        Schema::create('JENIS_MEDIA', function (Blueprint $table) {
            $table->string('ID_JENIS_MEDIA', 20)->primary();
            $table->string('JENIS_MEDIA', 200);
            $table->char('STATUS', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('JENIS_MEDIA');
    }
};
