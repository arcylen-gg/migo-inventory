<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCashbackPointsDistribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cashback_points_distribute', function (Blueprint $table) 
        {
            $table->increments('cashback_distribute_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('log_wallet_id')->unsigned();
            $table->integer('slot_id');
            $table->double('amount_distributed');
            $table->integer('distribute_batch');
            $table->dateTime('date_created');
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
