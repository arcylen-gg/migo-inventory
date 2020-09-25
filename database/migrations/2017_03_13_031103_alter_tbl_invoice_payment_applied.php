<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblInvoicePaymentApplied extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            $table->dropColumn("inv_payment_applied");
        });

        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            $table->double("inv_payment_applied")->after("inv_overall_price");
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
