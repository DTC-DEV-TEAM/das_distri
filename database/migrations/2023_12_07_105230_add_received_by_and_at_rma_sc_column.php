<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceivedByAndAtRmaScColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header_distribution', function (Blueprint $table) {
            $table->integer('received_by_rma_sc')->unsigned()->nullable()->after('received_at_sc');
            $table->timestamp('received_at_rma_sc')->nullable()->after('received_by_rma_sc');

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
            $table->dropColumn('received_by_rma_sc');
            $table->dropColumn('received_at_rma_sc');
        });
    }
}
