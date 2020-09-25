<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblAdvertisementBonusSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_advertisement_bonus_settings', function (Blueprint $table) 
        {
            $table->increments('advertisement_bonus_settings_id');
            $table->integer('level_end')->default(2);
            $table->double('advertisement_income')->default(0);
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
