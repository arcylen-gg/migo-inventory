<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblCustomerInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) 
        {
            $table->integer('inv_shop_id')->after('inv_id');
            $table->double('inv_subtotal_price')->after('taxable');
            $table->double('inv_overall_price')->after('inv_subtotal_price');
            $table->integer('inv_custom_field_id')->after('inv_overall_price');
        });

        Schema::table('tbl_customer_invoice_line', function (Blueprint $table) 
        {   
            $table->double('invline_discount')->after('taxable');
            $table->string('invline_discount_remark')->after('invline_discount');
            $table->double('invline_amount')->after('invline_discount_remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            //
        });
    }
}
