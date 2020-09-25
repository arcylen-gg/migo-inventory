<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->increments('tbl_order_id');
            $table->integer('customer_id')->nullable();
            $table->double('discount',18,2);
            $table->string('discount_var', 10);
            $table->string('discount_reason');
            $table->tinyInteger('IsfreeShipping');
            $table->integer('shipping_name');
            $table->double('shipping_amount',18,2);
            $table->tinyInteger('isTaxExempt');
            $table->tinyInteger('hasTax');
            $table->double('tax_percentage');
            $table->text('notes');
            $table->string('payment_stat',50);
            $table->string('payment_method');
            $table->tinyInteger('IsReserve');
            $table->datetime('reserve_date');
            $table->datetime('craeted_date');
            $table->string('status',5);
            $table->integer('shop_id')->unsigned();
            $table->string('fulfillment_status');
            $table->text('proof_of_payment');
            $table->datetime('date_approve_order');
            $table->tinyInteger('archived');

        });
         Schema::table('tbl_order', function($table) {
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
        Schema::drop('tbl_order');
    }
}
