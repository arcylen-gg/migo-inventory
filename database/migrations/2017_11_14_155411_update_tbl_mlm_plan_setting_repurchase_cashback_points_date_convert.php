<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMlmPlanSettingRepurchaseCashbackPointsDateConvert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_plan_setting', function (Blueprint $table) 
        {
           $table->integer('repurchase_cashback_date_convert')->default(0);
        });

        Schema::create('tbl_mlm_cashback_convert_history', function (Blueprint $table) 
        {
           $table->increments('cashback_convert_history_id');
           $table->dateTime('cashback_convert_history_date');
           $table->integer('shop_id')->unsigned();
           
           $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onCascade('delete');
        });


        Schema::create('rel_cashback_convert_history', function (Blueprint $table) 
        {
           $table->increments('cashback_convert_history_id');
           $table->integer('rel_points_log_id')->unsigned();
           $table->integer('rel_cashback_convert_history_id')->unsigned();
           $table->integer('shop_id')->unsigned();
           $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onCascade('delete');
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
