<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVmoneyWalletLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vmoney_wallet_logs', function (Blueprint $table) 
        {
            $table->increments('vmoney_wallet_logs_id');
            $table->dateTime('vmoney_wallet_logs_date');
            $table->string('vmoney_wallet_logs_email');
            $table->double('vmoney_wallet_logs_amount');
            // $table->integer('shop_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            
            // $table->foreign('shop_id')
            //       ->references('shop_id')->on('tbl_shop')
            //       ->onDelete('cascade');
            
            $table->foreign('customer_id')
                  ->references('customer_id')->on('tbl_customer')
                  ->onDelete('cascade');
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
