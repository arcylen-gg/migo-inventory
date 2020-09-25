<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblTempCustomerInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_temp_customer_invoice', function (Blueprint $table) {
            $table->integer("new_inv_id")->after("inv_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_temp_customer_invoice', function (Blueprint $table) {
            //
        });
    }
}
