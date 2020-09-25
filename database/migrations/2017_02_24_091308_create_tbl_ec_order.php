<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ec_order', function (Blueprint $table) 
        {
            $table->increments('ec_order_id');
            $table->integer('customer_id')->unsigned();
            $table->string('customer_email');
            $table->string('billing_address');
            $table->dateTime('invoice_date');
            $table->dateTime('due_date');
            $table->string('invoice_message');
            $table->string('statement_memo');
            $table->double('subtotal');
            $table->double('ewt');
            $table->double('discount_amount_from_product');
            $table->double('discount_amount');
            $table->string('discount_type');
            $table->double('discount_coupon_amount');
            $table->double('discount_coupon_type');
            $table->double('total');

            $table->tinyInteger('tax');
            $table->integer('coupon_id')->nullable()->unsigned();
            $table->integer('term_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->dateTime('created_date');
            $table->tinyInteger('archived');
        });

        Schema::create('tbl_ec_order_item', function (Blueprint $table) 
        {
            $table->increments('ec_order_item_id');
            $table->integer('item_id')->unsigned();
            $table->double('price');
            $table->integer('quantity');
            $table->double('subtotal');
            $table->double('discount_amount');
            $table->double('discount_type');
            $table->string('remark');
            $table->double('total');
            $table->string('description');
            $table->dateTime('service_date');
            $table->tinyInteger('tax');
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
