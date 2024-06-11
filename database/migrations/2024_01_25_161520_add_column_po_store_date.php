<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPoStoreDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header', function (Blueprint $table) {
            $table->timestamp('po_store_date')->nullable()->after('case_status');
        });
        Schema::table('returns_header_retail', function (Blueprint $table) {
            $table->timestamp('po_store_date')->nullable()->after('case_status');
        });
        Schema::table('returns_header_distribution', function (Blueprint $table) {
            $table->timestamp('po_store_date')->nullable()->after('case_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns_header', function (Blueprint $table) {
            $table->dropColumn('po_store_date');
        });
        Schema::table('returns_header_retail', function (Blueprint $table) {
            $table->dropColumn('po_store_date');
        });
        Schema::table('returns_header_distribution', function (Blueprint $table) {
            $table->dropColumn('po_store_date');
        });
    }
}
