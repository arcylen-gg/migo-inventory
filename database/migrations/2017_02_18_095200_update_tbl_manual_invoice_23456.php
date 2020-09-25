<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblManualInvoice23456 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_manual_invoice', function (Blueprint $table) {
            $table->dropForeign("tbl_manual_invoice_inv_id_foreign");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_manual_invoice', function (Blueprint $table) {
            //
        });
    }
}
