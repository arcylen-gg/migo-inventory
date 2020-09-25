<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMerchantSchoolWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merchant_school_wallet', function (Blueprint $table) {
            $table->increments('merchant_school_id');
            $table->double('merchant_school_amount')->default(0);
            $table->string('merchant_school_s_id')->nullable();
            $table->string('merchant_school_s_name')->nullable(0);
            $table->string('merchant_school_remarks')->nullable(0);
            $table->datetime('merchant_school_date')->useCurrent();
            $table->integer('merchant_school_custmer_id')->integer(0);
            $table->integer('merchant_school_slot_id')->integer(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
