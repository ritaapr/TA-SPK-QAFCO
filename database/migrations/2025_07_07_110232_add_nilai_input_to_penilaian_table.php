<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNilaiInputToPenilaianTable extends Migration
{
    public function up()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->float('nilai_input')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropColumn('nilai_input');
        });
    }
}
