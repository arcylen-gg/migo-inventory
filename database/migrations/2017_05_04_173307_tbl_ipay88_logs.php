<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblIpay88Logs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ipay88_logs', function (Blueprint $table) 
        {
            $table->increments('log_id');
            $table->integer('log_reference_number');
            $table->integer('log_amount');
            $table->text('log_description');
            $table->integer('shop_id')->unsigned();

            $table->foreign('shop_id')
                  ->references('shop_id')->on('tbl_shop')
                  ->onDelete('cascade');
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
