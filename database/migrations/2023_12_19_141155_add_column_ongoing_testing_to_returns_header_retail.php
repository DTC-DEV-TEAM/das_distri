<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOngoingTestingToReturnsHeaderRetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header_retail', function (Blueprint $table) {
            $table->timestamp('ongoing_testing_date')->nullable()->after('assigned_date_by_tech_lead');
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
            $table->dropColumn('ongoing_testing_date');
        });
    }
}