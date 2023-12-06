<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScIdToReturnsHeaderRetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header_retail', function (Blueprint $table) {
            $table->integer('sc_location_id')->unsigned()->nullable()->after('stores_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns_header_retail', function (Blueprint $table) {
            $table->dropColumn('sc_location_id');
        });
    }
}
