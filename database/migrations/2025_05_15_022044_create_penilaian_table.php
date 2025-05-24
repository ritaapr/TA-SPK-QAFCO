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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmi_id')->constrained('cpmi')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('subkriteria_id')->constrained('subkriteria')->onDelete('cascade');
            $table->timestamps();

            // Optional: untuk mencegah penilaian ganda
            $table->unique(['cpmi_id', 'kriteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
