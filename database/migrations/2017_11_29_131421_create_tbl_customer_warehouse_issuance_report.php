<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCustomerWarehouseIssuanceReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_wis', function (Blueprint $table) {
            $table->increments('cust_wis_id');
            $table->integer('cust_wis_shop_id')->unsigned();
            $table->string('cust_wis_number');
            $table->integer('destination_customer_id')->unsigned();
            $table->text('destination_customer_address');
            $table->integer('cust_wis_from_warehouse')->unsigned();
            $table->longtext('cust_wis_remarks');
            $table->string('cust_wis_status')->default('pending');
            $table->text('cust_confirm_image');
            $table->string('cust_receiver_code');
            $table->string('cust_ref_name');
            $table->integer('cust_ref_id');
            $table->date('cust_delivery_date');
            $table->timestamps();
            
            $table->foreign('destination_customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('cust_wis_from_warehouse')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
            $table->foreign('cust_wis_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });

        Schema::create('tbl_customer_wis_item', function (Blueprint $table) {
            $table->increments('cust_wis_item_id');
            $table->integer('cust_wis_id')->unsigned();
            $table->integer('cust_wis_record_log_id');

            $table->foreign('cust_wis_id')->references('cust_wis_id')->on('tbl_customer_wis')->onDelete('cascade');

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_customer_warehouse_issuance_report');
    }
}
