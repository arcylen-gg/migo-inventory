<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSirCmItemV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sir_cm_item', function (Blueprint $table) {
            $table->increments('s_cm_item_id');
            $table->integer('sc_sir_id')->unsigned();
            $table->integer('sc_item_id');
            $table->integer('sc_item_qty');
            $table->integer('sc_physical_count');
            $table->double('sc_item_price');
            $table->integer('sc_status');
            $table->tinyInteger('sc_is_updated');
            $table->double('sc_infos');

            $table->foreign("sc_sir_id")->references("sir_id")->on("tbl_sir")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_sir_cm_item');
    }
}
