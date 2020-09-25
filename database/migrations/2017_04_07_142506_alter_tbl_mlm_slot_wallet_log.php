<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmSlotWalletLogv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_slot_wallet_log', function (Blueprint $table) {
            //
            if(!schema::hasColumn('tbl_mlm_slot_wallet_log','wallet_log_selected'))
            {
                $table->integer('wallet_log_selected')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_slot_wallet_log', function (Blueprint $table) {
            //
        });
    }
}
