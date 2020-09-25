<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblIpay88Temp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ipay88_temp', function (Blueprint $table) {
            $table->increments('temp_id');
            $table->integer('reference_number');
            $table->integer('shop_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->datetime('date_created');
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
