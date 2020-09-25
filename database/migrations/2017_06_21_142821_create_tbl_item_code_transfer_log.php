<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemCodeTransferLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_code_transfer_log', function (Blueprint $table) 
        {
            $table->increments('item_code_transfer_log_id');
            $table->integer('item_code_transfer_by');
            $table->integer('item_code_transfer_to');
            $table->integer('item_code_id');
            $table->dateTime('item_code_transfer_date'); 
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
