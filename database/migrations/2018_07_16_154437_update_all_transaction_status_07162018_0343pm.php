<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAllTransactionStatus071620180343pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*VENDOR*/
        Schema::table('tbl_requisition_slip', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_receive_inventory', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_debit_memo', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_pay_bill', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });

        /*CUSTOMER*/
        Schema::table('tbl_customer_estimate', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_customer_wis', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_receive_payment', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });


        /*ITEM*/
        Schema::table('tbl_warehouse_issuance_report', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_warehouse_receiving_report', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
        });
        Schema::table('tbl_inventory_adjustment', function (Blueprint $table) {
            $table->string('transaction_status')->nullable();
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
