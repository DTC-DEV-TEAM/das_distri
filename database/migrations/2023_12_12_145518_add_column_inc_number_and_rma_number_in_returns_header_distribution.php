<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIncNumberAndRmaNumberInReturnsHeaderDistribution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header_distribution', function (Blueprint $table) {
            $table->string('inc_number')->nullable()->after('comments');
            $table->string('rma_number')->nullable()->after('inc_number');
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
            $table->dropColumn('inc_number');
            $table->dropColumn('rma_number');
        });
    }
}
