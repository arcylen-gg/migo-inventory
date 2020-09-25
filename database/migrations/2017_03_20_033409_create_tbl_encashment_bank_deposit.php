<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEncashmentBankDeposit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_encashment_bank_deposit', function (Blueprint $table) {
            $table->increments('encashment_bank_deposit_id');
            $table->integer('shop_id')->unsigned();
            $table->string('encashment_bank_deposit_name');
            $table->integer('encashment_bank_deposit_archive')->default(0);
        });
        Schema::table('tbl_mlm_encashment_settings', function (Blueprint $table) {
            $table->integer('enchasment_settings_cheque_edit')->default(0);        
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
