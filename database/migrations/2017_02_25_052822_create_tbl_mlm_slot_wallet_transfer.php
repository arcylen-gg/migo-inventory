<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSlotWalletTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_slot_wallet_log_transfer', function (Blueprint $table) {
            $table->increments('wallet_log_transfer_id');
            $table->double('wallet_log_transfer_amount');
            $table->double('wallet_log_transfer_fee');
            $table->integer('wallet_log_transfer_slot_trans');
            $table->integer('wallet_log_transfer_slot_recieve');
            $table->datetime('wallet_log_transfer_date');
            $table->string('wallet_log_transfer_remarks');
            $table->string('wallet_log_transfer_remarks_admin');
            $table->integer('shop_id')->unsigned();
        });
        Schema::table('tbl_mlm_slot_wallet_log_refill_settings', function (Blueprint $table) {
            $table->double("wallet_log_refill_settings_transfer_processing_fee")->defaul(0);
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
