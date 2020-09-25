<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionDiscountValue031320181025AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->float('po_discount_value')->change();
        });
        Schema::table('tbl_receive_inventory', function (Blueprint $table) {
            $table->float('ri_discount_value')->change();
        });
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->float('bill_discount_value')->change();
        });
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->float('wc_discount_value')->change();
        });
        Schema::table('tbl_debit_memo', function (Blueprint $table) {
            $table->float('db_discount_value')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            //
        });
    }
}
