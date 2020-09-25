<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMerchantCommision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merchant_commission', function (Blueprint $table) 
        {
            $table->increments('merchant_commission_id');
            $table->integer('merchant_commission_user_request')->default(0);
            $table->integer('merchant_commission_user_send_request')->default(0);
            $table->double('merchant_commission_amount')->default(0);
            $table->string('merchant_commission_status')->default('Requested');
            $table->string('merchant_commission_remarks')->default('Requested');


            $table->datetime('merchant_commission_request_date')->nullable();
            $table->datetime('merchant_commission_deny_date')->nullable();
            $table->datetime('merchant_commission_approve_date')->nullable();

        });
        Schema::table('tbl_item_code_invoice', function (Blueprint $table) {
            //
            $table->integer('merchant_commission_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_merchant_commission');
    }
}
