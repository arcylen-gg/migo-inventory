<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblDirectGcLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_direct_gc_logs', function (Blueprint $table) 
        {
            $table->increments('gc_logs_id');
            $table->double('gc_log_amount')->default(0);
            $table->integer('slot_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->dateTime('date_created');
            $table->tinyInteger('gc_claimed')->default(0);
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
