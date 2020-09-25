<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmBinaryPairing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_binary_pairing', function (Blueprint $table) {
            $table->increments('pairing_id');
            $table->double('pairing_point_left');
            $table->double('pairing_point_right');
            $table->double('pairing_bonus');
            $table->integer('membership_id')->unsigned();
            $table->foreign('membership_id')->references('membership_id')->on('tbl_membership');
            $table->integer('pairing_archive');
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
