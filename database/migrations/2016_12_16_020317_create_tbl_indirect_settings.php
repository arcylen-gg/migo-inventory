<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblIndirectSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_indirect_setting', function (Blueprint $table) {
            $table->increments('indirect_seting_id');
            $table->integer('indirect_seting_level')->unsigned();
            $table->double('indirect_seting_value');
            $table->tinyinteger('indirect_seting_percent');
            $table->integer('membership_id')->references('membership_id')->on('tbl_membership');
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
