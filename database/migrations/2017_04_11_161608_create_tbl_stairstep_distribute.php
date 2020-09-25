<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblStairstepDistribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_stairstep_distribute', function (Blueprint $table) 
        {
            $table->increments('stairstep_distribute_id');
            $table->dateTime('stairstep_distribute_start_date');
            $table->dateTime('stairstep_distribute_end_date');
            $table->integer('shop_id')->unsigned();
            $table->dateTime('date_created');
            $table->tinyInteger('complete');
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
