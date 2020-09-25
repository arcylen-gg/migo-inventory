<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblECommerceBanking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ecommerce_banking', function (Blueprint $table) {
            $table->increments('ecommerce_banking_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->string('ecommerce_banking_name');
            $table->string('ecommerce_banking_account_name');
            $table->string('ecommerce_banking_account_number');
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
        Schema::drop('tbl_ecommerce_banking');
    }
}
