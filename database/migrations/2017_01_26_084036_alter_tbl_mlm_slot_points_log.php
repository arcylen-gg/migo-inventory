<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmSlotPointsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_slot_points_log', function (Blueprint $table) {
            //
            $table->integer('points_log_leve_start')->default(0);
            $table->integer('points_log_leve_end')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_slot_points_log', function (Blueprint $table) {
            //
        });
    }
}
