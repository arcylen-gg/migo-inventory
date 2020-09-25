<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmUnilevelSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_unilevel_settings', function (Blueprint $table) {
            $table->increments('unilevel_settings_id');
            $table->double('unilevel_settings_level')->default(0);
            $table->double('unilevel_settings_amount')->default(0);
            $table->tinyinteger('unilevel_settings_percent')->default(0);
            $table->integer('membership_id')->unsigned();
            $table->foreign('membership_id')->references('membership_id')->on('tbl_membership');
            $table->integer('unilevel_settings_archive');
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
