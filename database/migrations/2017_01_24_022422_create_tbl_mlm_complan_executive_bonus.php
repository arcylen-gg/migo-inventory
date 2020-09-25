<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmComplanExecutiveBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_complan_executive_settings', function (Blueprint $table) 
        {
            $table->increments("executive_settings_id");
            
            $table->double("membership_id")->unsigned();
            $table->double("executive_settings_required_points");
            $table->double("executive_settings_bonus");
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
