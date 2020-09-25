<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayoutBankShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('tbl_payout_bank_shop', function (Blueprint $table)
        {
            $table->integer('payout_bank_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->foreign('payout_bank_id')->references('payout_bank_id')->on('tbl_payout_bank')->onDelete('cascade');
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
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
