<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCouponCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_coupon_code', function (Blueprint $table) 
        {
            $table->increments("coupon_code_id");
            $table->integer("id_per_coupon");
            $table->string("coupon_code");
            $table->double("coupon_code_amount");
            $table->integer("shop_id")->unsigned();
            $table->dateTime("date_created");
            $table->tinyInteger("used");
            $table->tinyInteger("blocked");
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
