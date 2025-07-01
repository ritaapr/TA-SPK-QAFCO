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
        Schema::table('cpmi', function (Blueprint $table) {
            $table->renameColumn('ktp', 'foto_profil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpmi', function (Blueprint $table) {
            $table->renameColumn('foto_profil', 'ktp');
        });
    }
};