<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVmoneySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vmoney_settings', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->string('vmoney_email');
            $table->integer('slot_id')->unsigned();

            $table->foreign('slot_id')
                  ->references('slot_id')->on('tbl_mlm_slot')
                  ->onDelete('cascade');
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
