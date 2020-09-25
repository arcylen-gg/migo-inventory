<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemCodeInvoiceAddPaymentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_code_invoice', function (Blueprint $table) {
            $table->integer('item_code_payment_type')->default(0)->after('item_code_statement_memo');
            $table->double('item_code_tendered_payment')->default(0)->after('item_code_statement_memo');
            $table->double('item_code_change')->default(0)->after('item_code_statement_memo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_item_code_invoice', function (Blueprint $table) {
            //
        });
    }
}
