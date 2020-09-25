<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSlotWalletRefillSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_wallet_log_refill_settings', function (Blueprint $table) {
            $table->increments('wallet_log_refill_settings_id');
            $table->double('wallet_log_refill_settings_processings_fee');
            $table->integer('wallet_log_refill_settings_processings_max_request');
            $table->integer('shop_id')->unsigned();
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
