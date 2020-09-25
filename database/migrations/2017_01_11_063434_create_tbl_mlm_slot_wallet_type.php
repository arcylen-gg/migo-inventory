<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSlotWalletType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_wallet_type', function (Blueprint $table) 
        {
            $table->increments('wallet_type_id');
            $table->string('wallet_type_key')->default('cash');
            $table->tinyinteger('wallet_type_enable_encash')->default(0);
            $table->tinyinteger('wallet_type_enable_product_repurchase')->default(0);
            $table->tinyinteger('wallet_type_other')->default(0);
            $table->tinyinteger('wallet_type_archive')->default(0);
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
