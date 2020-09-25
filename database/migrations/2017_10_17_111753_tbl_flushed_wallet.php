<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblFlushedWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_flushed_wallet', function (Blueprint $table) 
        {
            $table->increments('flushed_id');
            $table->double('flushed_amount');
            $table->integer('flushed_by')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->dateTime('flushed_date_created');
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
