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
        Schema::create('COMPLAINT_DIREKSI', function (Blueprint $table) {
            $table->string('ID_DIREKSI', 20)->primary();
            $table->string('NAMA', 100);
            $table->string('NO_TLPN', 15);
            $table->string('KET', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('COMPLAINT_DIREKSI');
    }
};
