<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWriteCheckV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_write_check', function (Blueprint $table) 
        {
            $table->increments('wc_id');
            $table->integer('wc_new_id');

            $table->integer("wc_shop_id");
            $table->integer("wc_vendor_id");
            $table->integer("wc_ap_account");
            $table->double("wc_total_amount");
            $table->integer("wc_payment_method");
            $table->date("wc_payment_date");
            $table->tinyInteger("wc_is_paid");
            $table->double("wc_applied_payment");
            $table->string("wc_memo");
            $table->datetime("date_created");
        });
        Schema::create('tbl_write_check_line', function (Blueprint $table) 
        {
            $table->increments('wcline_id');

            $table->integer("wcline_wc_id")->unsigned();
            $table->integer("wcline_item_id");
            $table->text("wcline_description");
            $table->integer("wcline_qty");
            $table->integer("wcline_um");
            $table->double("wcline_rate");
            $table->double("wcline_amount");
            $table->string("wcline_ref_name");
            $table->integer("wcline_ref_id");

            $table->foreign('wcline_wc_id')->references('wc_id')->on('tbl_write_check')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_write_check');
    }
}
