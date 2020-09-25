<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPurchaseOrder1115667 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->renameColumn("po_ap_acccount","po_ap_account");
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
