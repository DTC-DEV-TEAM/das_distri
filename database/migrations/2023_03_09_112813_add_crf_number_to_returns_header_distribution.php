<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCrfNumberToReturnsHeaderDistribution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header_distribution', function (Blueprint $table) {
            //
            $table->string('dr_number')->nullable()->after('pos_crf_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns_header_distribution', function (Blueprint $table) {
            //
            $table->dropColumn('dr_number');
        });
    }
}
