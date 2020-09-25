<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_voucher_item', function (Blueprint $table) {
            $table->dropColumn('voucher_item_subtotal');
            $table->dropColumn('voucher_item_discount');
            $table->dropColumn('voucher_item_total');
            $table->double('voucher_item_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_voucher_item', function (Blueprint $table) {
            //
        });
    }
}
