<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmLeadershipSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_leadership_settings', function (Blueprint $table) 
        {
            $table->increments("leadership_settings_id");
            
            $table->double("leadership_settings_start");
            $table->double("leadership_settings_end");
            $table->double("leadership_settings_earnings");
            $table->double("leadership_settings_required_points");

            $table->integer("membership_id")->unsigned();
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
