<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcRecentlyViewedProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ec_recently_viewed_products', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->dateTime('date');

            $table->foreign('customer_id')
                  ->references('customer_id')->on('tbl_customer')
                  ->onDelete('cascade');

            $table->foreign('shop_id')
                  ->references('shop_id')->on('tbl_shop')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('eprod_id')->on('tbl_ec_product')
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
