<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblStairstepPointsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_stairstep_points_log', function (Blueprint $table) 
        {
            $table->increments('stairstep_points_log_id');
            $table->double('stairstep_points_amount');
            $table->double('stairstep_percentage');
            $table->double('stairstep_reduced_percentage');
            $table->integer('stairstep_reduced_by_id');
            $table->integer('stairstep_reduced_rank');
            $table->integer('stairstep_cause_id');
            $table->integer('current_rank');
            $table->integer('slot_points_log_id');
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
