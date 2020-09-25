<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmWalletLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_wallet_log', function (Blueprint $table) {
            $table->increments('wallet_log_id');
            
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            
            $table->integer('wallet_log_slot')->unsigned()->nullable();
            $table->foreign('wallet_log_slot')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->integer('wallet_log_slot_sponsor')->unsigned()->nullable();
            $table->foreign('wallet_log_slot_sponsor')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->datetime('wallet_log_date_created');
            $table->string('wallet_log_details')->default('No Details Available');
            $table->double('wallet_log_amount')->default(0);
            $table->string('wallet_log_plan')->default('No Plan');
            $table->string('wallet_log_status')->default('n_ready');
            $table->datetime('wallet_log_claimbale_on');
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
