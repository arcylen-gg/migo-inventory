<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponCodeAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_coupon_code', function (Blueprint $table) {
            $table->integer("coupon_product_id")->nullable()->default(null)->after('coupon_code');
            $table->integer("coupon_minimum_quantity");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_coupon_code', function (Blueprint $table) {
            //
        });
    }
}
