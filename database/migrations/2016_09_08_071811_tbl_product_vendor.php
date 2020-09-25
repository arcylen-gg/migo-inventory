<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblProductVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_product_vendor', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('vendor_id');
            $table->string('vendor_name');
            $table->integer('vendor_shop')->unsigned();
            $table->dateTime('vendor_date_created');
            $table->foreign('vendor_shop')->references('shop_id')->on('tbl_shop');
            $table->tinyInteger('archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_product_vendor');
    }
}
