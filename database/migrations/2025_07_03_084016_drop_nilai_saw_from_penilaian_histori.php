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
    Schema::table('penilaian_histori', function (Blueprint $table) {
        $table->dropColumn('nilai_saw');
    });
}

public function down()
{
    Schema::table('penilaian_histori', function (Blueprint $table) {
        $table->double('nilai_saw')->nullable();
    });
}

};
