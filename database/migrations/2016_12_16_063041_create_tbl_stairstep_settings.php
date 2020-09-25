<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblStairstepSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_stairstep_settings', function (Blueprint $table) {
            $table->increments('stairstep_id');
            $table->integer('stairstep_level');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->string('stairstep_name');
            $table->double('stairstep_required_pv');
            $table->double('stairstep_required_gv');
            $table->double('stairstep_bonus');
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
