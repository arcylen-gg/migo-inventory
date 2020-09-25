<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmDiscountCadLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_discount_card_log', function (Blueprint $table) {
            $table->increments('discount_card_log_id');
            $table->datetime('discount_card_log_date_created');
            $table->datetime('discount_card_log_date_used')->nullable();
            $table->integer('discount_card_slot_sponsor');
            $table->integer('discount_card_customer_sponsor');
            $table->integer('discount_card_membership');
            $table->integer('discount_card_customer_holder')->nullable();
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
