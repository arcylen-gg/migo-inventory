<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMlmPlanSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_mlm_plan_setting', function (Blueprint $table) {
            $table->increments('plan_settings_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->tinyinteger('plan_settings_enable_mlm')->default(0);
            $table->tinyinteger('plan_settings_enable_replicated')->default(0);
            $table->tinyinteger('plan_settings_slot_id_format')->default(0);
            $table->string('plan_settings_format')->default(0);
            $table->integer('plan_settings_prefix_count')->default(0);
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
