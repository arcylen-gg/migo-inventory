<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmWalletLogAddProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_slot_wallet_log', function (Blueprint $table) {
            $table->integer("encashment_process")->unsigned()->nullable();
            $table->integer("encashment_process_type")->unsigned()->defaul(0);
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
