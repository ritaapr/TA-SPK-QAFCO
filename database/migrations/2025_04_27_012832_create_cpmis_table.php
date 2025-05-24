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
        Schema::create('cpmi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cpmi');
            $table->string('nik', 16)->unique();
            $table->text('alamat');
            $table->string('no_hp');
            $table->date('tanggal_daftar');
            $table->string('ktp'); // untuk menyimpan nama file KTP
            $table->timestamps();
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpmis');
    }
};
