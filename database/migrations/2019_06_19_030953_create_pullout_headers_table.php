<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulloutHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullout_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference',50)->nullable();                     //reference_number
            $table->string('st_number_pull_out',50)->nullable();            //st_number_pull_out
            $table->string('sor_number',50)->nullable();                    //sor_number
            $table->string('mor_number',50)->nullable();                    //mor_number
            $table->string('wrf_number',50)->nullable();
            $table->date('wrf_date')->nullable();

            $table->date('requested_date')->nullable();                     //request_date
            $table->date('pull_out_date')->nullable();                      //requested_date_for_pull_out
            $table->date('pull_out_schedule_date')->nullable();
            
            

            $table->integer('channels_id')->unsigned()->nullable();         //channel_name
            $table->integer('transaction_types_id')->unsigned()->nullable();//designation

            //store id
            $table->integer('pull_out_from')->unsigned()->nullable();       //pull_out_from
            $table->integer('pull_out_deliver_to')->unsigned()->nullable(); //pull_out_deliver_to

            //foreign keys with relationship
            $table->integer('reasons_id')->unsigned()->nullable();          //pull_out_reason
            $table->integer('paths_id')->unsigned()->nullable();            //pull_out_via
            //persons
            $table->integer('ops_personnel')->unsigned()->nullable();       //ops_name
            $table->integer('merchandiser_personnel')->unsigned()->nullable();//merchandiser_name
            $table->integer('logistics_personnel')->unsigned()->nullable();
            $table->integer('received_by')->unsigned()->nullable();         //received_by
            $table->dateTime('received_at')->nullable();

            //foreign keys pullout status
            $table->integer('status_level1')->unsigned()->nullable();       //pull_out_status_level1
            $table->integer('status_level2')->unsigned()->nullable();       //pull_out_status_level2
            $table->integer('status_level3')->unsigned()->nullable();       //pull_out_status_level3
            $table->integer('status_level4')->unsigned()->nullable();       //pull_out_status_level4

            $table->text('comments')->nullable();                           //request_comment

            //approver per levels
            $table->integer('personnel_level1')->unsigned()->nullable();       //pull_out_status_level1
            $table->dateTime('approved_at_level1')->nullable();   
            $table->dateTime('rejected_at_level1')->nullable();

            $table->integer('personnel_level2')->unsigned()->nullable();       //pull_out_status_level2
            $table->dateTime('approved_at_level2')->nullable();
            $table->dateTime('rejected_at_level2')->nullable();

            $table->integer('personnel_level3')->unsigned()->nullable();       //pull_out_status_level3
            $table->dateTime('approved_at_level3')->nullable();
            $table->dateTime('rejected_at_level3')->nullable();
            
            $table->integer('personnel_level4')->unsigned()->nullable();
            $table->dateTime('approved_at_level4')->nullable();
            $table->dateTime('rejected_at_level4')->nullable();
            //dates
            
            $table->string('released_by',50)->nullable();
            $table->date('released_date')->nullable();
            $table->string('scanned_by',50)->nullable();
            
            $table->integer('created_by')->unsigned()->nullable();          //requestor_name
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
        Schema::dropIfExists('pullout_headers');
    }
}
