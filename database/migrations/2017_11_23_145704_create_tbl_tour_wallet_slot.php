<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTourWalletSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tour_wallet_slot', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('tour_wallet_id')->unsigned();
            $table->integer('slot_id')->unsigned();
            $table->string('tour_wallet_account_id');

            $table->foreign('tour_wallet_id')
                  ->references('tour_wallet_id')->on('tbl_tour_wallet')
                  ->onDelete('cascade');

            $table->foreign('slot_id')
                  ->references('slot_id')->on('tbl_mlm_slot')
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
