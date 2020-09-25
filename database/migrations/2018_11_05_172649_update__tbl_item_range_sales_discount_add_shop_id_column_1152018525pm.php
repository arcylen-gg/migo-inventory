<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemRangeSalesDiscountAddShopIdColumn1152018525pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_range_sales_discount', function (Blueprint $table) {
            $table->integer("range_shop_id")->unsigned();
            $table->foreign("range_shop_id")->references("shop_id")->on("tbl_shop")->onCascade("delete");
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
