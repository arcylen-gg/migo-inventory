<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMerchantCommissionAddCommissionRequestFrom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_merchant_commission', function (Blueprint $table) {
            //
            $table->datetime('merchant_commission_request_from')->nullable();
            $table->datetime('merchant_commission_request_to')->nullable();
            $table->string('merchant_commission_request_remarks')->nullable();
            $table->string('merchant_commission_request_proof')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_merchant_commission', function (Blueprint $table) {
            //
        });
    }
}
