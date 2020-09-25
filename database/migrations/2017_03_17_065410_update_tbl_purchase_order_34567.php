<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPurchaseOrder34567 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->tinyInteger("po_is_paid")->after("po_overall_price");
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
