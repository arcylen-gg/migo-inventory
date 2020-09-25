<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblRankUpdateAndSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_rank_update', function (Blueprint $table)
        {
            $table->increments('rank_update_id');
            $table->integer('total_slots');
            $table->integer('shop_id');
            $table->tinyInteger('complete');
            $table->dateTime('date_created');
        });

        Schema::create('tbl_rank_update_slot', function (Blueprint $table)
        {
            $table->integer('rank_update_id');
            $table->integer('slot_id');
            $table->double('rank_personal_pv');
            $table->double('rank_group_pv');
            $table->integer('required_leg_rank_id');
            $table->integer('current_leg_rank_count');
            $table->integer('new_rank_id');
            $table->integer('old_rank_id');
            $table->dateTime('date_created');
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
