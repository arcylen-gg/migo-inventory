<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTourWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_tour_wallet', function (Blueprint $table) {
            $table->increments('tour_wallet_id');
            $table->integer('tour_wallet_shop')->default(0);
            $table->integer('tour_wallet_customer_id')->default(0);
            $table->integer('tour_wallet_user_id')->default(0);
            $table->string('tour_Wallet_a_account_id')->nullable();
            $table->string('tour_wallet_a_username')->nullable();
            $table->text('tour_wallet_a_base_password')->nullable();
            $table->double('tour_wallet_a_current_balance')->default(0);
            $table->integer('tour_wallet_block')->default(0);
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
