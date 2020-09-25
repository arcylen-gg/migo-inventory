<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUmV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_um', function (Blueprint $table) {
            $table->increments('id');
            $table->string('um_name');
            $table->string('um_abbrev');
            $table->tinyInteger('is_based');
            $table->integer('um_shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_um');
    }
}
