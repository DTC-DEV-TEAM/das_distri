<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTechLeadColumnAndDateToReturnsHeaderRetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header_retail', function (Blueprint $table) {
            $table->integer('assigned_by_tech_lead_id')->unsigned()->nullable()->after('rma_specialist_date_received');
            $table->date('assigned_date_by_tech_lead')->nullable()->after('assigned_by_tech_lead_id');
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
            $table->dropColumn('assigned_by_tech_lead_id');
            $table->dropColumn('assigned_date_by_tech_lead');
        });
    }
}