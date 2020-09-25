<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPurchaseOrder3456789 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->string("po_vendor_email");
            $table->renameColumn("po_customer_billing_address","po_billing_address");
        });

        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            $table->string("poline_discount_remark");
            $table->double("poline_discount");
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
