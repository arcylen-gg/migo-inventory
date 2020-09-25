<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemProductPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_item_points', function (Blueprint $table) {
            $table->increments('item_points_id');
            $table->double('item_points_stairstep');
            $table->double('item_points_unilevel');
            $table->double('item_points_binary');
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('item_id')->on('tbl_item');
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
