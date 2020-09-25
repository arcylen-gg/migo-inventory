<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_product', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('product_id');
            $table->integer('product_shop')->unsigned();
            $table->string('product_name');
            $table->text('product_description');
            $table->tinyInteger('product_main')->default(1);
            $table->tinyInteger('product_has_variations')->default(0); //this product has multiple variations
            $table->integer('product_type')->unsigned();
            $table->integer('product_vendor')->unsigned();
            $table->text('product_search_keyword');
            $table->dateTime('product_date_created');
            $table->dateTime('product_date_visible');
            $table->integer('popularity');
            
            $table->foreign('product_shop')->references('shop_id')->on('tbl_shop');
            $table->foreign('product_vendor')->references('vendor_id')->on('tbl_product_vendor');
            $table->foreign('product_type')->references('type_id')->on('tbl_product_type');
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
        Schema::drop("tbl_product");
    }
}
