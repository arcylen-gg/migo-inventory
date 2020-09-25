<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmSlotPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_points_log', function (Blueprint $table) 
        {
            $table->increments("points_log_id");
            $table->string("points_log_complan");
            $table->integer("points_log_level")->default(0);
            $table->integer("points_log_slot");
            $table->integer("points_log_Sponsor");
            $table->datetime("points_log_date_claimed");
            $table->integer("points_log_converted")->default(0);
            $table->datetime("points_log_converted_date");
            $table->string("points_log_type");
            $table->string("points_log_from");
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
