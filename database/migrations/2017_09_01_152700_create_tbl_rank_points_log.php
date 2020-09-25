<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblRankPointsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_rank_points_log', function (Blueprint $table) 
        {
            $table->increments('rank_points_log_id');
            $table->double('rank_original_amount');
            $table->double('rank_percentage_used');
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
