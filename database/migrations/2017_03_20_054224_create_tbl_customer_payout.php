<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCustomerPayout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_payout', function (Blueprint $table) {
            $table->increments('customer_payout_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('customer_payout_type')->default(0);
            $table->string('customer_payout_name_on_cheque');
            $table->integer('encashment_bank_deposit_id')->unsigned();
            $table->string('customer_payout_bank_branch');
            $table->string('customer_payout_bank_account_number');
            $table->string('customer_payout_bank_account_name');
        });
        Schema::table('tbl_mlm_encashment_settings', function (Blueprint $table) {
            $table->integer('enchasment_settings_bank_edit')->default(0);        
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
