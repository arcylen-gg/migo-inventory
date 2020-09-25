<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerInvoiceV89 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            $table->integer("sale_receipt_cash_account")->after("is_sales_receipt");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            //
        });
    }
}
