<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmTransferSlotLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_transfer_slot_log', function (Blueprint $table) 
        {
            $table->increments('transfer_slot_log_id');
            $table->integer("slot_transfer_by");
            $table->integer("slot_transfer_to");
            $table->integer("slot_id");
            $table->datetime('transfer_slot_date');
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
