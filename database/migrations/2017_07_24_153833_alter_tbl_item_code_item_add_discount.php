<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblItemCodeItemAddDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_code_item', function (Blueprint $table) {
            //
            $table->double('item_markup_percent_less_discount')->default(0);
            $table->double('item_markup_value_less_discount')->default(0);
            $table->double('item_markup_collectibles')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_item_code_item', function (Blueprint $table) {
            //
        });
    }
}
