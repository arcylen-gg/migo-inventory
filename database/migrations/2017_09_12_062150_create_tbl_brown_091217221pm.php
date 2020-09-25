<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblBrown091217221pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_brown_rank', function (Blueprint $table) {
            $table->increments('rank_id');
            $table->string('rank_name');
            $table->integer('rank_shop_id');
            $table->integer('required_slot');
            $table->string('required_uptolevel');
            $table->double('builder_reward_percentage');
            $table->string('builder_uptolevel');
            $table->double('leader_override_build_reward');
            $table->string('leader_override_build_uptolevel');
            $table->double('leader_override_direct_reward');
            $table->string('leader_override_direct_uptolevel');
            $table->datetime('rank_created');
            $table->tinyInteger('archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tbl_brown', function (Blueprint $table) {
            //
        });
    }
}
