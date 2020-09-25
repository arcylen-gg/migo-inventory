<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayBillV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pay_bill', function (Blueprint $table) {
            $table->increments('paybill_id');
            $table->integer('paybill_shop_id')->unsigned();
            $table->integer('paybill_vendor_id');
            $table->integer('paybill_ap_id');
            $table->date('paybill_date');
            $table->double('paybill_total_amount');
            $table->string('paybill_payment_method');
            $table->longtext('paybill_memo');
            $table->datetime('paybill_date_created');
        });
        Schema::create('tbl_pay_bill_line', function (Blueprint $table) {
            $table->increments('pbline_id');
            $table->integer("pbline_pb_id")->unsigned();
            $table->string("pbline_reference_name");
            $table->integer("pbline_reference_id");
            $table->double("pbline_amount");

            $table->foreign("pbline_pb_id")->references("paybill_id")->on("tbl_pay_bill")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_pay_bill');
    }
}
