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
        Schema::create('UNIT_KERJA', function (Blueprint $table) {
            $table->string('ID_BAGIAN', 5)->primary();
            $table->string('NAMA_BAGIAN', 200)->nullable(false);
            $table->string('NAMA_BAGIAN_SINGULAR', 250);
            $table->string('NAMA_ALTERNATIF', 150)->nullable();
            $table->string('ID_PARENT_BAGIAN', 5);
            $table->integer('SUPER')->default(38);
            $table->integer('STATUS')->default(38);
            $table->date('TGL_INSROW')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('UNIT_KERJA');
    }
};
