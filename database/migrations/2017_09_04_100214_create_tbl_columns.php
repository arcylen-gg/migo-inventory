<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_columns', function (Blueprint $table) 
        {
            $table->increments('columns_id');
            $table->binary('columns_data');
            $table->string('columns_from');
            $table->integer('shop_id')->unsigned();

            $table->foreign("shop_id")->references('shop_id')->on('tbl_shop')->onDelete('cascade');
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
