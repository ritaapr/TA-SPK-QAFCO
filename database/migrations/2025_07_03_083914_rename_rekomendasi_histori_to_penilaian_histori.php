<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::rename('rekomendasi_histori', 'penilaian_histori');
}

public function down()
{
    Schema::rename('penilaian_histori', 'rekomendasi_histori');
}

};
