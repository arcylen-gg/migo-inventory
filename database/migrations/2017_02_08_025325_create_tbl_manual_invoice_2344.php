<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblManualInvoice2344 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_manual_invoice', function (Blueprint $table) {
            $table->increments('manual_invoice_id');
            $table->integer("sir_id")->unsigned();
            $table->integer("inv_id")->unsigned();
            $table->datetime("manual_invoice_date");

            $table->foreign('sir_id')
                  ->references('sir_id')->on('tbl_sir')
                  ->onDelete('cascade');

            $table->foreign('inv_id')
                  ->references('inv_id')->on('tbl_customer_invoice')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_manual_invoice');
    }
}
