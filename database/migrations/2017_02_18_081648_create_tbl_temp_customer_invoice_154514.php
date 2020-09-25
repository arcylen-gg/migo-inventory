<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTempCustomerInvoice154514 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_temp_customer_invoice', function (Blueprint $table) 
        {
            $table->increments('inv_id');
            $table->integer('inv_shop_id');
            $table->integer('inv_customer_id');
            $table->integer('inv_customer_email');
            $table->string('inv_customer_billing_address');
            $table->tinyInteger('inv_terms_id');
            $table->date('inv_date');
            $table->date('inv_due_date');
            $table->string('inv_message');
            $table->string('inv_memo');
            $table->string('inv_discount_type');
            $table->integer('inv_discount_value');
            $table->double('ewt');
            $table->tinyInteger('taxable');
            $table->double('inv_subtotal_price');
            $table->double('inv_overall_price');
            $table->integer('inv_custom_field_id');
            $table->datetime('date_created');
            $table->timestamps();
        });

        Schema::create('tbl_temp_customer_invoice_line', function (Blueprint $table)
        {
            $table->increments('invline_id');  
            $table->integer('invline_inv_id')->unsigned();
            $table->datetime('invline_service_date');
            $table->integer('invline_item_id');
            $table->string('invline_description');
            $table->integer('invline_um');
            $table->integer('invline_qty');
            $table->double('invline_discount');
            $table->string('invline_discount_remark');
            $table->double('invline_rate');
            $table->double('invline_amount');
            $table->tinyInteger('taxable');
            $table->datetime('date_created');

            $table->foreign('invline_inv_id')->references('inv_id')->on('tbl_temp_customer_invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_temp_customer_invoice');
    }
}
