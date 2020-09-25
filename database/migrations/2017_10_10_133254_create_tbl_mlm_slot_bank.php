<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSlotBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_bank', function (Blueprint $table)
        { 
            $table->increments('mlm_slot_bank_id');
            $table->integer('slot_id')->unsigned();
            $table->integer("payout_bank_id")->unsigned();
            $table->string("bank_account_name");
            $table->string("bank_account_number");
            $table->foreign('slot_id')->references('slot_id')->on('tbl_mlm_slot')->onDelete('cascade');
            $table->foreign('payout_bank_id')->references('payout_bank_id')->on('tbl_payout_bank')->onDelete('cascade');
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
