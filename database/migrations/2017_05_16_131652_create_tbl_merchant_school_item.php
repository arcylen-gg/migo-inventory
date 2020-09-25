<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMerchantSchoolItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_merchant_school_item', function (Blueprint $table) {
            $table->increments('merchant_school_item_id');
            $table->integer('merchant_school_item_shop');
            $table->integer('merchant_item_item_id');
            $table->integer('merchant_item_ec_order_id');

            $table->integer('merchant_item_customer_id');
            $table->integer('merchant_item_slot_id');
            $table->string('merchant_item_code');
            $table->integer('merchant_item_pin');
            $table->datetime('merchant_item_date');


            $table->integer('merchant_item_status')->default(0);
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
