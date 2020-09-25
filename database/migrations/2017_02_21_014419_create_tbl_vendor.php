<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendor', function (Blueprint $table) {
            $table->increments('vendor_id');
            $table->integer('vendor_shop_id')->unsigned();
            $table->string('vendor_title_name', 100)->nullable();
            $table->string('vendor_first_name')->nullable();
            $table->string('vendor_middle_name')->nullable();
            $table->string('vendor_last_name')->nullable();
            $table->string('vendor_suffix_name', 100);
            $table->string('vendor_email')->nullable();
            $table->string('vendor_company')->nullable();
            $table->date('created_date');
            $table->tinyInteger('archived');

            $table->foreign('vendor_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });

        Schema::create('tbl_vendor_address', function (Blueprint $table) {
            $table->increments('ven_addr_id');
            $table->integer('ven_addr_vendor_id')->unsigned();
            $table->integer('ven_billing_country_id')->nullable()->unsigned();    
            $table->string('ven_billing_state')->nullable();
            $table->string('ven_billing_city')->nullable();
            $table->string('ven_billing_zipcode')->nullable();
            $table->text('ven_billing_street')->nullable();
            $table->integer('ven_shipping_country_id')->nullable()->unsigned();    
            $table->string('ven_shipping_state')->nullable();
            $table->string('ven_shipping_city')->nullable();
            $table->string('ven_shipping_zipcode')->nullable();
            $table->text('ven_shipping_street')->nullable();

            $table->foreign('ven_addr_vendor_id')->references('vendor_id')->on('tbl_vendor')->onDelete('cascade');
            $table->foreign('ven_billing_country_id')->references('country_id')->on('tbl_country')->onDelete('cascade');
            $table->foreign('ven_shipping_country_id')->references('country_id')->on('tbl_country')->onDelete('cascade');
        });

        Schema::create('tbl_vendor_other_info', function (Blueprint $table) {
            $table->increments('ven_info_id');
            $table->integer('ven_info_vendor_id')->unsigned();
            $table->string('ven_info_phone')->nullable();
            $table->string('ven_info_mobile')->nullable();
            $table->string('ven_info_fax')->nullable();
            $table->string('ven_info_other_contact')->nullable();
            $table->string('ven_info_website')->nullable();
            $table->string('ven_info_display_name')->nullable();
            $table->string('ven_info_print_name')->nullable();
            $table->string('ven_info_billing')->nullable();
            $table->string('ven_info_tax_no')->nullable();
            $table->integer('ven_info_payment_method')->nullable();
            $table->integer('ven_info_delivery_method')->nullable();
            $table->integer('ven_info_terms')->nullable();
            $table->float('ven_info_opening_balance', 18,2)->nullable();
            $table->date('ven_info_balance_date')->nullable();
            
            $table->foreign('ven_info_vendor_id')->references('vendor_id')->on('tbl_vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_vendor');
    }
}
