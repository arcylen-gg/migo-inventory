<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItmMulitplePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_multiple_price', function (Blueprint $table) {
            $table->increments('multiprice_id');
            $table->integer('multiprice_item_id')->unsigned();
            $table->integer('multiprice_qty');
            $table->double('multiprice_price');
            $table->datetime('date_created');

            $table->foreign('multiprice_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_item_multiple_price');
    }
}
