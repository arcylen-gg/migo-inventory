<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmUnilevelPointsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_unilevel_points_settings', function (Blueprint $table) {
            $table->increments('unilevel_points_id');
            $table->integer('unilevel_points_level')->default(0);
            $table->double('unilevel_points_amount')->default(0);
            $table->tinyinteger('unilevel_points_percentage')->default(0);
            $table->integer('unilevel_points_archive')->default(0);
            $table->integer('membership_id')->unsigned();
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
