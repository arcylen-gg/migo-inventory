<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblDebitMemo020720180756pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_debit_memo_line', function (Blueprint $table) {
            $table->string("dbline_refname")->nullable();
            $table->integer("dbline_refid")->default(0);
        });
        Schema::table('tbl_requisition_slip_item', function (Blueprint $table) {
            $table->string("rs_item_refname")->nullable();
            $table->integer("rs_item_refid")->default(0);
        });
        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            $table->string("poline_refname")->nullable();
            $table->integer("poline_refid")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_debit_memo_line', function (Blueprint $table) {
            //
        });
    }
}
