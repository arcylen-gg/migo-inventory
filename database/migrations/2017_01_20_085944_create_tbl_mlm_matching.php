<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmMatching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_matching', function (Blueprint $table) 
        {
            $table->increments("matching_settings_id");
            
            $table->double("matching_settings_start");
            $table->double("matching_settings_end");
            $table->double("matching_settings_earnings");

            $table->integer("membership_id")->unsigned();
            $table->integer("shop_id")->unsigned();
        }); 
        Schema::create('tbl_mlm_matching_log', function (Blueprint $table) 
        {
            $table->increments("matching_log");
            
            $table->integer('matching_log_membership_1');
            $table->integer('matching_log_membership_2');

            $table->integer('matching_log_level_1');
            $table->integer('matching_log_level_2');

            $table->integer('matching_log_slot_1');
            $table->integer('matching_log_slot_2');

            $table->integer('matching_log_earner');
            $table->integer('matching_log_earning');
                
            $table->integer("shop_id")->unsigned();
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
