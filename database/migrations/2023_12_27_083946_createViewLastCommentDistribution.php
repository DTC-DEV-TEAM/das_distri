<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewLastCommentDistribution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("DROP VIEW IF EXISTS distri_last_comments;");

        DB::statement("
            CREATE VIEW distri_last_comments AS
            select chat_distri.`returns_header_distri_id` AS `returns_header_distri_id`,
            max(chat_distri.`id`) AS `chats_id` from chat_distri 
            group by chat_distri.`returns_header_distri_id`
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("DROP VIEW IF EXISTS distri_last_comments;");
    }
}
