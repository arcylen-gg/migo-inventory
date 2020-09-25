<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblBillItemLine34567 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->integer("itemline_poline_id")->after("itemline_item_id");
        });
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->integer("itemline_po_id")->after("itemline_poline_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            //
        });
    }
}
