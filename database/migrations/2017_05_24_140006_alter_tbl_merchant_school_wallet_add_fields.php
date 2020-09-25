<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMerchantSchoolWalletAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_merchant_school_wallet', function (Blueprint $table) {
            $table->double('merchant_school_amount_old')->default(0);
            $table->double('merchant_school_amount_new')->default(0);
            $table->string('merchant_school_anouncement')->nullable();
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
