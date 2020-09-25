<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCollectionItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_collection_item', function (Blueprint $table) {
            $table->increments('collection_item_id');
            $table->integer('collection_id')->unsigned();
            $table->integer('variant_id')->unsigned();
            $table->tinyInteger('hide');
            $table->tinyInteger('archived');
            $table->datetime('date_created');
        });
        
        Schema::table('tbl_collection_item', function(Blueprint $table) {
          $table->foreign('collection_id')->references('collection_id')->on('tbl_collection')->onDelete('cascade');
          $table->foreign('variant_id')->references('variant_id')->on('tbl_variant')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_collection_item');
    }
}
