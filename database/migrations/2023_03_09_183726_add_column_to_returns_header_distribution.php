<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToReturnsHeaderDistribution extends Migration
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
            $table->date('pickup_schedule')->nullable()->after('return_schedule');
            $table->integer('via_id')->nullable()->after('return_delivery_date');
            $table->string('carried_by')->nullable()->after('via_id');
            $table->string('pos_crf_number')->nullable()->after('sor_number');
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
            Schema::table('returns_header_distribution', function($table){
                $table->dropColumn('pickup_schedule');
                $table->dropColumn('via_id');
                $table->dropColumn('carried_by');
                $table->dropColumn('pos_crf_number');
                $table->dropColumn('dr_number');
            });
        });
    }
}
