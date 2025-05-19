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
        Schema::create('USER_COMPLAINT', function (Blueprint $table) {
            $table->string('NO_REGISTER', 20);
            $table->string('ID_BAGIAN', 5)->nullable(false);
            $table->string('USERNAME', 50)->nullable(false);
            $table->string('PASSWORD', 50)->nullable(false);
            $table->string('NAME', 100);
            $table->string('GROUPS', 50)->nullable();
            $table->string('SPESIAL_CODE', 50)->nullable();
            $table->date('TGL_INSROW');
            $table->string('NIP', 15)->nullable(false);
            $table->string('VALIDASI', 1);
            $table->string('NO_TLPN', 15);
            $table->date('TGL_UPDATE');
            $table->string('PASSWORD_REAL', 255);
            $table->timestamps();

            $table->foreign('ID_BAGIAN')->references('ID_BAGIAN')->on('UNIT_KERJA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('USER_COMPLAINT');
    }
};
