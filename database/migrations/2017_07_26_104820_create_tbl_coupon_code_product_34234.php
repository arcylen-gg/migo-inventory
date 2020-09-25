<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCouponCodeProduct34234 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_coupon_code_product', function (Blueprint $table) {
            $table->increments('cc_id');
            $table->integer("coupon_code_id");
            $table->integer("coupon_code_product_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tvl_coupon_code_product');
    }
}
