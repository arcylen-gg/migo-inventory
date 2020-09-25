<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRefillableTransactionBinLocation4262018426PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_receiving_report_itemline', function (Blueprint $table) {
            $table->integer("rr_sub_wh_id")->unsigned()->nullable()->after("rr_description");
            $table->foreign("rr_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouse_receiving_report_itemline', function (Blueprint $table) {
            //
        });
    }
}
