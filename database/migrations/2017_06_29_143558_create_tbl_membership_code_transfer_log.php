<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembershipCodeTransferLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership_code_transfer_log', function (Blueprint $table) 
        {
            $table->increments('membership_code_transfer_log_id');
            $table->integer('membership_code_transfer_by');
            $table->integer('membership_code_transfer_to');
            $table->integer('membership_code_id');
            $table->dateTime('membership_code_transfer_date'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
