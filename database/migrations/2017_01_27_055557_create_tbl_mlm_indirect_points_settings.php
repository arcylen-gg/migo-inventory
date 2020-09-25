<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmIndirectPointsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_indirect_points_settings', function (Blueprint $table) 
        {
            $table->increments('indirect_points_id');
            $table->integer('indirect_points_level');
            $table->double('indirect_points_value')->default(0);
            $table->integer('membership_id')->unsigned();
            $table->integer('indirect_points_archive')->default(0);
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
