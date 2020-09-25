<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblVoucherAndTblVoucherItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_voucher', function (Blueprint $table) {
            $table->increments('voucher_id');
            $table->string('voucher_code');
            $table->integer('voucher_code_type')->default(0);
            $table->integer('voucher_invoice_membership_id')->nullable();
            $table->integer('voucher_invoice_product_id')->nullable();

            $table->integer('voucher_slot')->nullable();
            $table->integer('voucher_customer');

            $table->integer('voucher_claim_status')->default(0);
            $table->double('voucher_sub_total')->default(0);
            $table->double('voucher_discount')->default(0);
            $table->double('voucher_total')->default(0);
        });

        Schema::create('tbl_voucher_item', function (Blueprint $table) {
            $table->increments('voucher_item_id');
            $table->integer('voucher_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->double('voucher_item_subtotal')->default(0);
            $table->double('voucher_item_discount')->default(0);
            $table->double('voucher_item_total')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
