<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMerchantMarkUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merchant_markup', function (Blueprint $table) {
            $table->increments('merchant_markup_id');
            $table->integer('user_id')->default(0);
            $table->integer('shop_id')->default(0);
            $table->integer('item_id')->default(0);
            $table->double('item_price')->default(0);
            $table->double('item_markup_percentage')->default(0);
            $table->double('item_markup_value')->default(0);
            $table->double('item_after_markup')->default(0);
            $table->datetime('merchant_markup_date_created')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_merchant_markup');
    }
}
