<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMerchantEwallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merchant_ewallet', function (Blueprint $table) {
            $table->increments('merchant_ewallet_id');
            $table->integer('merchant_ewallet_user_request')->default(0);
            $table->integer('merchant_ewallet_user_send_request')->default(0);
            $table->integer('merchant_ewallet_amount')->default(0);
            $table->string('merchant_ewallet_status')->nullable();
            $table->string('merchant_ewallet_remarks')->nullable();
            $table->datetime('merchant_ewallet_request_date')->nullable();
            $table->datetime('merchant_ewallet_deny_date')->nullable();
            $table->datetime('merchant_ewallet_approve_date')->nullable();
            $table->datetime('merchant_ewallet_request_from')->nullable();
            $table->datetime('merchant_ewallet_request_to')->nullable();
            $table->string('merchant_ewallet_request_remarks')->nullable();
            $table->string('merchant_ewallet_request_proof')->nullable();
        });
        Schema::table('tbl_item_code_invoice', function (Blueprint $table) {
            //
            $table->integer('merchant_ewallet_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_merchant_ewallet');
    }
}
