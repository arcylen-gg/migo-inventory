<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_code_invoice', function (Blueprint $table) 
        {
            $table->increments('item_code_invoice_id');
            $table->string('item_code_invoice_number');
            $table->string('item_code_customer_email');
            $table->tinyInteger('item_code_paid');
            $table->tinyInteger('item_code_product_issued');
            $table->string('item_code_message_on_invoice');
            $table->string('item_code_statement_memo');
            $table->integer('shop_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->dateTime('item_code_date_created');

            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
        });
        
        Schema::create('tbl_item_code', function (Blueprint $table) 
        {
            $table->increments('item_code_id');
            $table->string('item_activation_code');
            $table->integer('customer_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('item_code_invoice_id')->unsigned();
            $table->tinyInteger('used');
            $table->tinyInteger('blocked');
            $table->tinyInteger('archived');
            $table->integer('shop_id')->unsigned();
            $table->string('item_code_pin');
            $table->double('item_code_price');
            $table->dateTime('date_used');

            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
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
