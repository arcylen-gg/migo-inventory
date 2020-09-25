<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblLeadershipAdvertisementBonusSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_leadership_advertisement_settings', function (Blueprint $table) 
        {
            $table->increments('leadership_advertisement_bonus_settings_id');
            $table->integer('left')->default(1);
            $table->integer('right')->default(1);
            $table->integer('level_start')->default(3);
            $table->double('leadership_advertisement_income')->default(0);
            $table->integer('shop_id')->unsigned();
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
