<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulloutLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullout_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('digits_code',10)->nullable();
            $table->string('upc_code',50)->nullable();
            $table->string('item_description',100)->nullable();
            $table->string('brand',50)->nullable();
            $table->string('category',50)->nullable();
            $table->integer('quantity')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pullout_lines');
    }
}
