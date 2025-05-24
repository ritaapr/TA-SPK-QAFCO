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
        Schema::create('subkriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->string('nama_subkriteria');
            $table->integer('batas_bawah')->nullable(); // Kalau nggak pakai rentang, bisa null
            $table->integer('batas_atas')->nullable();
            $table->integer('nilai'); // Nilai bobot untuk SAW (kayak 5, 4, 3, dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkriteria');
    }
};
