<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatEcommsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_ecomms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('returns_header_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('file_name')->length(255)->nullable();
            $table->enum('status',['ACTIVE','INACTIVE'])->nullable()->default('ACTIVE');
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('chat_ecomms');
    }
}
