<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_collection', function (Blueprint $table) {
            $table->increments('collection_id');
            $table->integer('shop_id')->unsigned();
            $table->string('collection_name');
            $table->text('note');
            $table->tinyInteger('hide');
            $table->tinyInteger('archived');
            $table->string('status',10);
            $table->datetime('date_created');
        });
        Schema::table('tbl_collection', function($table) {
           $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_collection');
    }
}
