<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblMlmBinarySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('tbl_mlm_binary_setttings', function (Blueprint $table) {
            $table->increments('binary_setttings');
            
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            
            $table->string('binary_settings_gc_enable')->default('disable');
            $table->integer('binary_settings_gc_every_pair')->default(0);
            $table->string('binary_settings_gc_title')->default('Fifth Pair GC');
            $table->double('binary_settings_gc_amount')->default(0);

            $table->integer('binary_settings_no_of_cycle')->default(1);
            $table->time('binary_settings_time_of_cycle');

            $table->string('binary_settings_strong_leg')->default('strong_leg');
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
