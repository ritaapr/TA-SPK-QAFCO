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
    Schema::table('rekomendasi', function (Blueprint $table) {
        $table->float('nilai_saw')->nullable()->after('cpmi_id');
    });
}

public function down()
{
    Schema::table('rekomendasi', function (Blueprint $table) {
        $table->dropColumn('nilai_saw');
    });
}

};
