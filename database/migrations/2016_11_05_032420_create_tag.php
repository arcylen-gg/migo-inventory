<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tags', function (Blueprint $table) {
            $table->increments('tag_id');
            $table->integer('product_id')->unsigned();
            $table->string('tags_name');
            $table->tinyInteger('archived');

        });
        Schema::table('tbl_tags', function($table) {
           $table->foreign('product_id')->references('product_id')->on('tbl_product');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_tags');
    }
}
