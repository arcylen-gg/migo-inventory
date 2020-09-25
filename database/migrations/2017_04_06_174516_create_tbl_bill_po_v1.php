<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblBillPoV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bill_po', function (Blueprint $table) {
            $table->increments('bill_po_id');
            $table->integer("billed_id")->unsigned();
            $table->integer("purchase_order_id")->unsigned();

            $table->foreign("billed_id")->references("bill_id")->on("tbl_bill")->onDelete('cascade');
            $table->foreign("purchase_order_id")->references("po_id")->on("tbl_purchase_order")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_bill_po');
    }
}
