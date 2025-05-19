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
        Schema::create('HUMAS', function (Blueprint $table) {
            $table->id('ID_HUMAS', 11)->primary();
            $table->string('USERNAME', 50)->nullable(false);
            $table->string('PASSWORD', 250)->nullable(false);
            $table->integer('NIP')->default(18)->nullable(false);
            $table->string('NAMA', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HUMAS');
    }
};
