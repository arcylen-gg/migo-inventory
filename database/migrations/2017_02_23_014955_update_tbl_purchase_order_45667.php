<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPurchaseOrder45667 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->double("po_subtotal_price");
            $table->double("po_overall_price");
            $table->integer("po_custom_field_id");
        });

        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            $table->integer("poline_um");
            $table->double("poline_amount");
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
