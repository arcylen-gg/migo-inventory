<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_purchase_order', function (Blueprint $table) {
            $table->increments('po_id');
            $table->integer('po_shop_id');
            $table->integer('po_vendor_id');
            $table->integer('po_ap_acccount');
            $table->longText('po_customer_billing_address');
            $table->tinyInteger('po_terms_id');
            $table->date('po_date');
            $table->date('po_due_date');
            $table->string('po_message');
            $table->string('po_memo');
            $table->string('po_discount_type');
            $table->integer('po_discount_value');
            $table->integer('ewt');
            $table->tinyInteger('taxable');
            $table->datetime('date_created');

        });

        Schema::create('tbl_purchase_order_line', function (Blueprint $table){
            $table->increments('poline_id');  
            $table->integer('poline_po_id')->unsigned();
            $table->datetime('poline_service_date');
            $table->integer('poline_item_id');
            $table->string('poline_description');
            $table->integer('poline_qty');
            $table->double('poline_rate');
            $table->tinyInteger('taxable');
            $table->datetime('date_created');

            $table->foreign('poline_po_id')->references('po_id')->on('tbl_purchase_order')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_purchase_order');
    }
}
