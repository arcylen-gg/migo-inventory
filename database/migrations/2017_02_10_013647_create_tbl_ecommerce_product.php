<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcommerceProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ec_product', function (Blueprint $table) {
            $table->increments('eprod_id');
            $table->integer('eprod_shop_id');
            $table->integer('eprod_is_single');
            $table->datetime('date_created');
        });

        Schema::create('tbl_ec_variant', function (Blueprint $table) {
            $table->increments('evariant_id');
            $table->integer('evariant_prod_id')->unsigned();
            $table->integer('evariant_item_id');
            $table->integer('evariant_item_label');
            $table->string('evariant_description');
            $table->double('evariant_price');
            $table->datetime('date_created');
            $table->datetime('date_visible');
            $table->timestamps();

            $table->foreign("evariant_prod_id")->references('eprod_id')->on('tbl_ec_product')->onDelete('cascade');
        });

        Schema::create('tbl_ec_variant_image', function (Blueprint $table) {
            $table->increments('eimg_id');
            $table->integer('eimg_variant_id');
            $table->integer('eimg_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_ecommerce_product');
    }
}
