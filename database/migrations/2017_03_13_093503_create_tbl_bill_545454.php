<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblBill545454 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bill', function (Blueprint $table) {
            $table->increments('bill_id');
            $table->integer("bill_shop_id");
            $table->integer("bill_vendor_id");
            $table->integer("bill_ap_account");
            $table->double("bill_total_amount");
            $table->integer("bill_payment_method");
            $table->string("bill_memo");
            $table->datetime("date_created");

        });

        Schema::create('tbl_bill_account_line', function (Blueprint $table) {
            $table->increments('accline_id');
            $table->integer("accline_bill_id")->unsigned();
            $table->integer("accline_coa_id");
            $table->text("accline_description");
            $table->double("accline_amount");

            $table->foreign('accline_bill_id')->references('bill_id')->on('tbl_bill')->onDelete('cascade');
        });
        Schema::create('tbl_bill_item_line', function (Blueprint $table) {
            $table->increments('itemline_id');
            $table->integer("itemline_bill_id")->unsigned();
            $table->integer("itemline_item_id");
            $table->text("itemline_description");
            $table->integer("itemline_um");
            $table->integer("itemline_qty");
            $table->double("itemline_rate");
            $table->double("itemline_amount");

            $table->foreign('itemline_bill_id')->references('bill_id')->on('tbl_bill')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_bill');
    }
}
