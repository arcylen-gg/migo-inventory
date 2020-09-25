<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblECommerceRemmittance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ecommerce_remittance', function (Blueprint $table) {
            $table->increments('ecommerce_remittance_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->string('ecommerce_remittance_name');
            $table->string('ecommerce_remittance_full_name');
            $table->string('ecommerce_remittance_address');
            $table->string('ecommerce_remittance_contact');
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
        Schema::drop('tbl_ecommerce_remittance');
    }
}
