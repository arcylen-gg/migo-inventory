<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblVendorTransaction122020171159am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            //Purchase Order
            $table->string("transaction_refnum")->after("po_id");
        });
        Schema::table('tbl_requisition_slip', function (Blueprint $table) {
            //Purchase Requisition
            $table->string("transaction_refnum")->after("requisition_slip_id");
        });
        Schema::table('tbl_bill', function (Blueprint $table) {
            //Receive Invty
            $table->string("transaction_refnum")->after("bill_id");
        });
        Schema::table('tbl_pay_bill', function (Blueprint $table) {
            //Pay Bills
            $table->string("transaction_refnum")->after("paybill_id");
        });
        Schema::table('tbl_write_check', function (Blueprint $table) {
            //Write Check
            $table->string("transaction_refnum")->after("wc_id");
        });
        Schema::table('tbl_debit_memo', function (Blueprint $table) {
            //Debit Memo
            $table->string("transaction_refnum")->after("db_id");
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
