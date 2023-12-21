<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewLastComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        DB::statement("DROP VIEW IF EXISTS new_items_last_comment;");

        DB::statement("
            CREATE VIEW retail_last_comments AS
            select `dtc_digits_das`.`chats`.`returns_header_retail_id` AS `returns_header_retail_id`,
            max(`dtc_digits_das`.`chats`.`id`) AS `chats_id` from `dtc_digits_das`.`chats` 
            group by `dtc_digits_das`.`chats`.`returns_header_retail_id`
        ");
    

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS retail_last_comments;");
    }
}
