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
        Schema::connection('chatbot')->create('data_chatbot', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->string('nama_file', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('chatbot')->dropIfExists('data_chatbot');
    }
};
