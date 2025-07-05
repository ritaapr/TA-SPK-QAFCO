<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('penilaian_histori', 'rekomendasi_histori');
    }

    public function down(): void
    {
        Schema::rename('rekomendasi_histori', 'penilaian_histori');
    }
};
