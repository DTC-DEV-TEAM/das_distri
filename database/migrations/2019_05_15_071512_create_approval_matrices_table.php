<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_matrices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cms_privileges_id')->unsigned();
            $table->integer('cms_users_id')->unsigned();
            $table->integer('groups_id')->unsigned();
            $table->integer('channels_id')->unsigned()->nullable();
            $table->integer('stores_id')->unsigned();
            $table->text('store_list')->nullable();
            $table->enum('status',['ACTIVE','INACTIVE'])->default('ACTIVE');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_matrices');
    }
}
