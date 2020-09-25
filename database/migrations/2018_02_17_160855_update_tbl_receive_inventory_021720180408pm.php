<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblReceiveInventory021720180408pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_receive_inventory', function (Blueprint $table) {
            $table->float('ri_subtotal')->after('ri_due_date')->default(0);
            $table->tinyInteger('taxable')->after('ri_subtotal')->default(0);
            $table->string('ri_discount_type')->after('taxable')->default(0);
            $table->integer('ri_discount_value')->after('ri_discount_type')->default(0);

        });

        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->float('wc_subtotal')->after('wc_cash_account')->default(0);
            $table->tinyInteger('taxable')->after('wc_subtotal')->default(0);
            $table->string('wc_discount_type')->after('taxable')->default(0);
            $table->integer('wc_discount_value')->after('wc_discount_type')->default(0);

        });

        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->float('bill_subtotal')->after('bill_due_date')->default(0);
            $table->tinyInteger('taxable')->after('bill_subtotal')->default(0);
            $table->string('bill_discount_type')->after('taxable')->default(0);
            $table->integer('bill_discount_value')->after('bill_discount_type')->default(0);

        });

        Schema::table('tbl_debit_memo', function (Blueprint $table) {
            $table->float('db_subtotal')->after('db_memo')->default(0);
            $table->tinyInteger('taxable')->after('db_subtotal')->default(0);
            $table->string('db_discount_type')->after('taxable')->default(0);
            $table->integer('db_discount_value')->after('db_discount_type')->default(0);

        });

        Schema::table('tbl_receive_inventory_line', function (Blueprint $table) {
            $table->float('riline_discount')->default(0);
            $table->string('riline_discounttype')->default(0);
        });

        Schema::table('tbl_write_check_line', function (Blueprint $table) {
            $table->float('wcline_discount')->default(0);
            $table->string('wcline_discounttype')->default(0);
        });

        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->float('itemline_discount')->default(0);
            $table->string('itemline_discounttype')->default(0);
        });

        Schema::table('tbl_debit_memo_line', function (Blueprint $table) {
            $table->float('dbline_discount')->default(0);
            $table->string('dbline_discounttype')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_receive_inventory', function (Blueprint $table) {
            //
        });
    }
}
