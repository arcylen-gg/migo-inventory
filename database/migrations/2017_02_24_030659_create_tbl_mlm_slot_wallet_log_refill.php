<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSlotWalletLogRefill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_wallet_log_refill', function (Blueprint $table) {
            $table->increments('wallet_log_refill_id');
            $table->datetime('wallet_log_refill_date');
            $table->datetime('wallet_log_refill_date_approved')->nullable();
            $table->double('wallet_log_refill_amount')->default(0);
            $table->double('wallet_log_refill_amount_paid')->default(0);
            $table->double('wallet_log_refill_processing_fee')->default(0);
            $table->integer('wallet_log_refill_approved')->default(0);
            $table->string('wallet_log_refill_remarks')->nullable();
            $table->string('wallet_log_refill_remarks_admin')->nullable();
            $table->string('wallet_log_refill_attachment')->nullable();
            $table->integer('shop_id')->unsigned();
            $table->integer('slot_id')->unsigned();
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
