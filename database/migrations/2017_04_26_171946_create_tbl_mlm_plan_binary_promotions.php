<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmPlanBinaryPromotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_plan_binary_promotions', function (Blueprint $table) {
            $table->increments('binary_promotions_id');
            $table->integer('binary_promotions_membership_id')->default(0);
            $table->integer('binary_promotions_item_id')->default(0);
            $table->integer('binary_promotions_required_left')->default(0);
            $table->integer('binary_promotions_required_right')->default(0);
            $table->integer('binary_promotions_no_of_units')->default(0);
            $table->integer('binary_promotions_no_of_units_used')->default(0);
            $table->datetime('binary_promotions_start_date');
            $table->datetime('binary_promotions_end_date');
            $table->integer('binary_promotions_day_duration')->default(0);
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
