<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmDiscountCardSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_discount_card_settings', function (Blueprint $table) {
            $table->increments('discount_card_id');
            $table->integer('discount_card_count_membership')->default(0);
            $table->integer('discount_card_membership')->nullable();
            $table->integer('discount_card_archive')->default(0);
            $table->integer('membership_id')->unsigned();
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
