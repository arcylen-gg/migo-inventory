<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblItemCodeItemAddMarkupPerMerchant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_code_item', function (Blueprint $table) {
            $table->double('item_markup_percent')->default(0);
            $table->double('item_markup_value')->default(0);
        });
        Schema::table('tbl_item_code_invoice', function (Blueprint $table) {
            $table->double('merchant_markup_value')->default(0);
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
