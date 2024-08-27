<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRmaReceiverSpecialistReturnsHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns_header', function (Blueprint $table) {
            $table->integer('rma_receiver_id')->length(11)->nullable()->after('level8_personnel_edited');
            $table->string('rma_receiver_date_received')->nullable()->after('rma_receiver_id');
            $table->integer('rma_specialist_id')->length(11)->nullable()->after('rma_receiver_date_received');
            $table->string('rma_specialist_date_received')->nullable()->after('rma_specialist_id');        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns_header', function (Blueprint $table) {
            $table->dropColumn('rma_receiver_id');
            $table->dropColumn('rma_receiver_date_received');
            $table->dropColumn('rma_specialist_id');
            $table->dropColumn('rma_specialist_date_received');
        });
    }
}
