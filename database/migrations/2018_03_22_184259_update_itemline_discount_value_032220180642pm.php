<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateItemlineDiscountValue032220180642pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_receive_inventory_line', function (Blueprint $table) {
            $table->float('riline_discount', 8, 4)->change();
        }); 

        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->float('itemline_discount', 8, 4)->change();
        });

        Schema::table('tbl_write_check_line', function (Blueprint $table) {
            $table->float('wcline_discount', 8, 4)->change();
        });

        Schema::table('tbl_debit_memo_line', function (Blueprint $table) {
            $table->float('dbline_discount', 8, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_receive_inventory_line', function (Blueprint $table) {
            //
        });
    }
}
