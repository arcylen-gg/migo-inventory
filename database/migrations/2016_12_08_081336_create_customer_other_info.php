<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOtherInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_other_info', function (Blueprint $table) {
            $table->increments('customer_other_info_id');
            $table->integer('customer_id')->unsigned();
            $table->string('customer_phone')->default('');
            $table->string('customer_mobile')->default('');
            $table->string('customer_fax')->default('');
            $table->string('customer_other_contact')->default('');
            $table->string('customer_website')->default('');
            $table->string('customer_display_name')->default('');
            $table->string('customer_print_name')->default('');
            $table->integer('customer_parent')->default(0);
            $table->string('customer_billing');
            
            $table->string('customer_tax_resale_no')->nullable();
            $table->integer('customer_payment_method')->default(0);
            $table->integer('customer_delivery_method')->default(0);
            $table->integer('customer_terms')->default(0);
            $table->double('customer_opening_balance', 18,2)->default(0);
            $table->date('customer_balance_date')->default('0000-00-00');
            
        });
        Schema::table('tbl_customer_other_info', function($table) {
           $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_customer_other_info');
    }
}
