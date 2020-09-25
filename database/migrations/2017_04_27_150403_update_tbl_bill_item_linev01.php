<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblBillItemLinev01 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->renameColumn("itemline_poline_id","itemline_ref_name");
            $table->renameColumn("itemline_po_id","itemline_ref_id");
        });
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->string("itemline_ref_name")->change();
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
