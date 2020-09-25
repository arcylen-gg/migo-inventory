<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_plan', function (Blueprint $table) {
            $table->increments('marketing_plan_code_id');
            $table->string('marketing_plan_code');
            $table->string('marketing_plan_name');
            $table->string('marketing_plan_trigger');
            $table->string('marketing_plan_label');
            $table->tinyInteger('marketing_plan_enable');
            $table->tinyInteger('marketing_plan_release_schedule');
            $table->integer('marketing_plan_release_monthly');
            $table->string('marketing_plan_release_weekly');
            $table->time('marketing_plan_release_time');
            $table->datetime('marketing_plan_release_schedule_date');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            
            // 
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
