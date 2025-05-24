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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria')->unique(); // Misal: K01, K02
            $table->string('nama_kriteria');
            $table->enum('jenis', ['benefit', 'cost']); // Tipe: benefit/cost
            $table->float('bobot', 8, 4)->nullable(); // Bisa null dulu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
