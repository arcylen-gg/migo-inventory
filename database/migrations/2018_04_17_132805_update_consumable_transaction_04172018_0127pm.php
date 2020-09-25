<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateConsumableTransaction041720180127pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_wis_item_line', function (Blueprint $table) {
            $table->integer("itemline_sub_wh_id")->unsigned()->nullable()->after("itemline_description");
            $table->foreign("itemline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_customer_invoice_line', function (Blueprint $table) {
            $table->integer("invline_sub_wh_id")->unsigned()->nullable()->after("invline_description");
            $table->foreign("invline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_debit_memo_line', function (Blueprint $table) {
            $table->integer("dbline_sub_wh_id")->unsigned()->nullable()->after("dbline_description");
            $table->foreign("dbline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_warehouse_issuance_report_itemline', function (Blueprint $table) {
            $table->integer("wt_sub_wh_id")->unsigned()->nullable()->after("wt_description");
            $table->foreign("wt_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_wis_item_line', function (Blueprint $table) {
            //
        });
    }
}
