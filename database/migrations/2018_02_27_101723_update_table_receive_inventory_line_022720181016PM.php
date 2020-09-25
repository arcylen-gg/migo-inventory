<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableReceiveInventoryLine022720181016PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_receive_inventory_line', function (Blueprint $table) {
            $table->tinyInteger('riline_taxable')->after('riline_rate')->default(0);
        });

        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->tinyInteger('itemline_taxable')->after('itemline_rate')->default(0);
        });

        Schema::table('tbl_write_check_line', function (Blueprint $table) {
            $table->tinyInteger('wcline_taxable')->after('wcline_rate')->default(0);
        });

        Schema::table('tbl_debit_memo_line', function (Blueprint $table) {
            $table->tinyInteger('dbline_taxable')->after('dbline_rate')->default(0);
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
