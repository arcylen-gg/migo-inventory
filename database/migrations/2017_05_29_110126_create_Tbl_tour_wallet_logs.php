<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTourWalletLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_tour_wallet_logs', function (Blueprint $table) 
        {
            $table->increments('tour_wallet_logs_id');
            $table->double('tour_wallet_logs_wallet_amount')->default(0);
            $table->datetime('tour_wallet_logs_date')->nullable();
            $table->integer('tour_wallet_logs_tour_id')->default(0);
            $table->string('tour_wallet_logs_account_id')->default(0);
            $table->integer('tour_wallet_logs_customer_id')->default(0);
            $table->integer('tour_wallet_logs_accepted')->default(0);
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
