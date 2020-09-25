<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSettingsDirectPromotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_plan_settings_direct_promotions', function (Blueprint $table) {
            $table->increments('settings_direct_promotions_id');
            $table->integer('membership_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->integer('settings_direct_promotions_count')->unsigned();
            $table->integer('settings_direct_promotions_bonus')->unsigned();
            $table->integer('settings_direct_promotions_type')->unsigned();
            $table->integer('settings_direct_promotions_archive')->unsigned();
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
