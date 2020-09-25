<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPriceLevelItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_price_level_item', function (Blueprint $table)
        {
            $table->increments('price_level_item_id');
            $table->integer('price_level_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->double('custom_price');
            $table->foreign('price_level_id')->references('price_level_id')->on('tbl_price_level')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
