<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewLastCommentsEcomm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS ecomm_last_comments;");

        DB::statement("
            CREATE VIEW ecomm_last_comments AS
            select chat_ecomms.`returns_header_id` AS `returns_header_id`,
            max(chat_ecomms.`id`) AS `chats_id` from chat_ecomms 
            group by chat_ecomms.`returns_header_id`
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {      
        DB::statement("DROP VIEW IF EXISTS ecomm_last_comments;");
    }
}
