<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('digits_code',60)->nullable();
            $table->string('upc_code',50)->nullable();
            $table->string('upc_code2',50)->nullable();
            $table->string('upc_code3',50)->nullable();
            $table->string('upc_code4',50)->nullable();
            $table->string('upc_code5',50)->nullable();
            $table->string('supplier_itemcode',50)->nullable();
            $table->string('item_description',100)->nullable();
            $table->integer('brand_id')->unsigned();
            //$table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            //$table->foreign('category_id')->references('id')->on('category');//->onDelete('cascade');
            $table->decimal('dtp_rf', 18, 2)->nullable();
            $table->decimal('current_srp', 18, 2)->nullable();
            $table->decimal('purchase_price', 18, 2)->nullable();
            $table->integer('skustatus_id')->unsigned();
            //$table->foreign('skustatus_id')->references('id')->on('skustatus');//->onDelete('cascade');
            $table->tinyInteger('segment_1',false,true)->default(0);
            $table->tinyInteger('segment_2',false,true)->default(0);
            $table->tinyInteger('segment_3',false,true)->default(0);
            $table->tinyInteger('segment_4',false,true)->default(0);
            $table->tinyInteger('segment_5',false,true)->default(0);
            $table->tinyInteger('segment_6',false,true)->default(0);
            $table->tinyInteger('segment_7',false,true)->default(0);
            $table->tinyInteger('segment_8',false,true)->default(0);
            $table->tinyInteger('segment_9',false,true)->default(0);
            $table->tinyInteger('segment_10',false,true)->default(0);
            $table->integer('dtc_wh',false,true)->length(10);
            $table->integer('dtc_rsv_qty',false,true)->length(10);
            $table->integer('onl_wh',false,true)->length(10);
            $table->integer('onl_rsv_qty',false,true)->length(10);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('items');
    }
}
