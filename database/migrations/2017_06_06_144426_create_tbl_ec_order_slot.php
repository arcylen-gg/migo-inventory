<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcOrderSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ec_order_slot', function (Blueprint $table) 
        {
            $table->increments('order_slot_id');
            $table->integer('order_slot_ec_order_id');
            $table->integer('order_slot_customer_id')->default(0);
            $table->integer('order_slot_used')->default(0);
            $table->integer('order_slot_sponsor')->default(0);
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
