<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblRankRepurchaseCashbackItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_rank_repurchase_cashback_item', function (Blueprint $table) 
        {
            $table->increments('rank_repurchase_cashback_item_id');
            $table->integer('item_id')->unsigned();
            $table->integer('rank_id')->unsigned();
            $table->double('amount')->default(0);
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
